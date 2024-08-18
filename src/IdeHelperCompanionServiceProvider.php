<?php

namespace IdeHelperCompanion;

use IdeHelperCompanion\Commands\IdeHelperCompanionCommand;
use IdeHelperCompanion\Commands\IdeHelperCompanionThrottledCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/*
 * This class is a Package Service Provider
 *
 * More info: https://github.com/spatie/laravel-package-tools
 */

class IdeHelperCompanionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('ide-helper-companion')
            ->hasConfigFile()
            ->hasCommand(IdeHelperCompanionCommand::class)
            ->hasCommand(IdeHelperCompanionThrottledCommand::class);
    }

    public function boot(): void
    {
        // Overwrite the config from the ide-helper package
        if (config('ide-helper-companion.overwrite_ide_helper.enabled')) {
            config()->set([
                'ide-helper.include_fluent' => config('ide-helper-companion.overwrite_ide_helper.include_fluent'),
            ]);
        }

        parent::boot();
    }
}
