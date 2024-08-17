<?php

namespace LaravelIdeHelperCompanion;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use LaravelIdeHelperCompanion\Commands\IdeHelperCompanionCommand;

/*
 * This class is a Package Service Provider
 *
 * More info: https://github.com/spatie/laravel-package-tools
 */
class LaravelIdeHelperCompanionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-ide-helper-companion')
            ->hasConfigFile()
            ->hasCommand(IdeHelperCompanionCommand::class);
    }
}
