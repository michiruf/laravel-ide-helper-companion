<?php

namespace IdeHelperCompanion\Commands;

use Barryvdh\LaravelIdeHelper\Console\ModelsCommand;
use Composer\ClassMapGenerator\ClassMapGenerator;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Workbench\App\Models\User;

class AnnotateCommand extends Command
{
    public $signature = 'ide-helper-companion:annotate';

    public $description = 'Command to annotate model files';

    public function handle(): int
    {
        $this->testIdeHelper();
//        $this->foo();
//        $this->readModelData();

        $this->annotate();

        return self::SUCCESS;
    }

    protected function getOptions()
    {
    }

    public function foo()
    {
        // TODO @see:
        $model = app(User::class);
        $cmd = new ModelsCommand(app(Filesystem::class));
        $cmd->setLaravel(app());
        $cmd->getPropertiesFromTable($model);

        dd();

        // TODO @see ModelsCommand#loadModels())
    }

    public function readModelData()
    {
        $model = app(User::class);

        $connection = $model->getConnection();
        $schema = $connection->getSchemaBuilder();
        $table = $model->getTable();
        $columns = $schema->getColumns($table);
        $indexes = $schema->getIndexes($table);

        //dd($columns);

        dd($model->primitiveCastTypes);

        return $columns;
    }

    public function annotate()
    {
    }
}
