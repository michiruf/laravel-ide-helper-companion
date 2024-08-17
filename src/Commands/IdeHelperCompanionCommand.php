<?php

namespace IdeHelperCompanion\Commands;

use Illuminate\Console\Command;

class IdeHelperCompanionCommand extends Command
{
    public $signature = 'ide-helper-companion';

    public $description = 'Convenience command to generate IDE helper files';

    public function handle(): int
    {
        // Fake the relevant config values
        config()->set([
            'ide-helper.filename' => 'vendor/laravel-ide-helper-companion/generated/_ide_helper.php',
            'ide-helper.models_filename' => 'vendor/laravel-ide-helper-companion/generated/_ide_helper_models.php',
            'ide-helper.meta_filename' => 'vendor/laravel-ide-helper-companion/generated/.phpstorm.meta.php',
            'ide-helper.include_fluent' => true,
        ]);

        // Call the regular commands
        //$this->call('ide-helper:eloquent');
        $this->call('ide-helper:generate');
        $this->call('ide-helper:meta');
        //$this->call('ide-helper:models');

        return self::SUCCESS;
    }
}
