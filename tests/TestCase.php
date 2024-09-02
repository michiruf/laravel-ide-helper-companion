<?php

namespace IdeHelperCompanion\Tests;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use CollectionDiffer\CollectionDifferServiceProvider;
use IdeHelperCompanion\IdeHelperCompanionServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;
use SplFileInfo;
use Workbench\App\Providers\WorkbenchServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'IdeHelperCompanion\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            IdeHelperServiceProvider::class,
            IdeHelperCompanionServiceProvider::class,
            CollectionDifferServiceProvider::class,
            WorkbenchServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        $path = __DIR__.'/../workbench/database/migrations/';
        collect(File::files($path))->each(function (SplFileInfo $file) use ($path) {
            $migration = include $path.$file->getFilename();
            $migration->up();
        });
    }
}
