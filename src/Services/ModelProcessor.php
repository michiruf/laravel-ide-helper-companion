<?php

namespace IdeHelperCompanion\Services;

use IdeHelperCompanion\Data\ClassDefinition;
use Workbench\App\Models\User;

class ModelProcessor
{
    public function __construct(
        public ClassDefinition $definition
    ) {
    }

    public function process(): void
    {
        $this
            ->loadModelData()
            ->generatePhpDoc();
    }

    public function loadModelData(): static
    {
        $model = app(User::class);

        $connection = $model->getConnection();
        $schema = $connection->getSchemaBuilder();
        $table = $model->getTable();
        $columns = $schema->getColumns($table);
        $indexes = $schema->getIndexes($table);

        return $this;
    }

    public function generatePhpDoc(): static
    {
        return $this;
    }
}
