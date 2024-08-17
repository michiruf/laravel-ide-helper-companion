<?php

namespace IdeHelperCompanion\Tests;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use IdeHelperCompanion\IdeHelperCompanionServiceProvider;
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
            WorkbenchServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-ide-helper-companion_table.php.stub';
        $migration->up();
        */
    }
}
