<?php

namespace IdeHelperCompanion\Services;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class ModelFieldTypeResolver
{
    public function translateDatabaseColumnToPhpType(array $column, Model $model): string
    {
        $dbType = $this->determineDatabaseColumnType($column, $model);

        return $this->castDatabaseColumn($dbType, $column, $model);
    }

    /**
     * @see ModelsCommand::getPropertiesFromTable()
     */
    public function determineDatabaseColumnType(array $column, Model $model): string
    {
        if (in_array($column['name'], $model->getDates())) {
            return $this->usedDateClass();
        }

        // Copied from the method mention in @see method doc
        // Match types to php equivalent
        return match ($column['type_name']) {
            'tinyint', 'bit',
            'integer', 'int', 'int4',
            'smallint', 'int2',
            'mediumint',
            'bigint', 'int8' => 'int',

            'boolean', 'bool' => 'bool',

            'float', 'real', 'float4',
            'double', 'float8' => 'float',

            default => 'string',
        };
    }

    /**
     * @see ModelsCommand::castPropertiesType()
     */
    public function castDatabaseColumn(string $type, array $column, Model $model): string
    {
        if (! method_exists($model, 'getCasts')) {
            return $type;
        }

        $cast = Arr::get($model->getCasts(), $column['name']);
        if (! $cast) {
            return $type;
        }

        if (Str::startsWith($cast, 'decimal:')) {
            $cast = 'decimal';
        } elseif (Str::startsWith($cast, 'custom_datetime:')) {
            $cast = 'date';
        } elseif (Str::startsWith($cast, 'date:')) {
            $cast = 'date';
        } elseif (Str::startsWith($cast, 'datetime:')) {
            $cast = 'date';
        } elseif (Str::startsWith($cast, 'immutable_custom_datetime:')) {
            $cast = 'immutable_date';
        } elseif (Str::startsWith($cast, 'immutable_date:')) {
            $cast = 'immutable_date';
        } elseif (Str::startsWith($cast, 'immutable_datetime:')) {
            $cast = 'immutable_datetime';
        } elseif (Str::startsWith($cast, 'encrypted:')) {
            $cast = Str::after($cast, ':');
        }

        switch ($cast) {
            case 'encrypted':
                return 'mixed';
            case 'boolean':
            case 'bool':
                return 'bool';
            case 'decimal':
            case 'string':
                return 'string';
            case 'array':
            case 'json':
                return 'array';
            case 'object':
                return 'object';
            case 'int':
            case 'integer':
            case 'timestamp':
                return 'int';
            case 'real':
            case 'double':
            case 'float':
                return 'float';
            case 'date':
            case 'datetime':
                return $this->usedDateClass();
            case 'immutable_date':
            case 'immutable_datetime':
                return '\Carbon\CarbonImmutable';
            case AsCollection::class:
            case AsEnumCollection::class:
            case 'collection':
                return '\Illuminate\Support\Collection';
            case AsArrayObject::class:
                return '\ArrayObject';
            default:
                // In case of an optional custom cast parameter, only evaluate until the ':'
                $cast = strtok($cast, ':');

                return class_exists($cast) ? ('\\'.$cast) : $type;
        }
    }

    public function usedDateClass(): string
    {
        return class_exists(Date::class)
            ? '\\'.get_class(Date::now())
            : '\Illuminate\Support\Carbon';
    }
}
