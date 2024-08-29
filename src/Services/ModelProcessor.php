<?php

namespace IdeHelperCompanion\Services;

use Barryvdh\Reflection\DocBlock;
use Barryvdh\Reflection\DocBlock\Context;
use Barryvdh\Reflection\DocBlock\Tag;
use Barryvdh\Reflection\DocBlock\Tag\PropertyTag;
use IdeHelperCompanion\Data\ClassDefinition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModelProcessor
{
    protected Collection $columns;

    protected DocBlock\Serializer $serializer;
    protected DocBlock $phpdoc;
    protected string $previousDocComment;

    public function __construct(
        public ClassDefinition $definition
    ) {
    }

    public function process(): void
    {
        $this->phpdoc = new DocBlock($this->definition->reflection(), new Context($this->definition->reflection()->getNamespaceName()));

        $this
            ->loadModelData()
            ->generatePhpDoc()
            ->writeNewPhpDoc();
    }

    public function loadModelData(): static
    {
        /** @var Model $model */
        $model = app($this->definition->classString);

        $connection = $model->getConnection();
        $schema = $connection->getSchemaBuilder();
        $this->columns = collect($schema->getColumns($model->getTable()));
        //$indexes = $schema->getIndexes($model->getTable());

        return $this;
    }

    public function generatePhpDoc(): static
    {
        /** @var Collection<int, PropertyTag> $properties */
        $properties = collect($this->phpdoc->getTags())
            ->filter(fn (DocBlock\Tag $tag) => //
                $tag->getName() === 'property'
                || $tag->getName() === 'property-read'
                || $tag->getName() === 'property-write'
            );

        $properties->differ($this->columns)
            ->identifySourceUsing(fn (PropertyTag $property) => $property->getVariableName())
            ->identifyDestinationUsing(fn (array $column) => $column['name'])
            ->handleUnmatchedSourceUsing(fn (PropertyTag $property) => $this->phpdoc->deleteTag($property))
            ->handleUnmatchedDestinationUsing(function (array $column) {
                $tagLine = trim("@property string \${$column['name']} {$column['comment']}");
                $tag = Tag::createInstance($tagLine, $this->phpdoc);
                $this->phpdoc->appendTag($tag);
            })
            ->handleMatchedUsing(function (PropertyTag $property, array $column) {
                $property->setType('string');
                $property->setVariableName($column['name']);
                $property->setDescription($column['description']);
            })
            ->diff();

        return $this;
    }

    public function writeNewPhpDoc(): static
    {
        $serializer = new DocBlock\Serializer();
        $contents = File::get($this->definition->filePath);
        $oldDocComment = $this->definition->reflection()->getDocComment();
        $newDocComment = $serializer->getDocComment($this->phpdoc);

        if ($oldDocComment) {
            $contents = Str::replace($oldDocComment, $newDocComment, $contents);
        } else {
            $className = $this->definition->reflection()->getShortName();
            $pos = strpos($contents, "final class $className") ?: strpos($contents, "class $className");
            if ($pos !== false) {
                $contents = substr_replace($contents, $newDocComment."\n", $pos, 0);
            }
        }

        File::put($this->definition->filePath, $contents, true);

        return $this;
    }

    public static function translateDatabaseColumnToPhpType(string $columnType): string
    {
        return '';
    }
}
