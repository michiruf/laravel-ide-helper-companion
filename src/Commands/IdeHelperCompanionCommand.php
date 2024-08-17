<?php

namespace IdeHelperCompanion\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'ide-helper-companion')]
class IdeHelperCompanionCommand extends Command
{
    public $signature = 'ide-helper-companion';

    public $description = 'Command to generate IDE helper files';

    public function handle(): int
    {
        // Ensure the directory exists
        if (config('ide-helper-companion.apply_base_directory')) {
            File::ensureDirectoryExists(base_path(config('ide-helper-companion.base_directory')));
        }

        // Clear the compiled cache, so the ide-helper commands will not fail
        $this->call('clear-compiled');

        // Call ide helper commands
        $this->ideHelperEloquentCommand();
        $this->ideHelperGenerateCommand();
        $this->ideHelperModelsCommand();
        $this->ideHelperMetaCommand();

        return self::SUCCESS;
    }

    protected function ideHelperEloquentCommand(): void
    {
        $this->call('ide-helper:eloquent');
    }

    protected function ideHelperGenerateCommand(): void
    {
        $this->call('ide-helper:generate', [
            'filename' => $this->filepath('filename'),
            '--write_mixins' => config('ide-helper-companion.write_mixins'),
        ]);
    }

    protected function ideHelperModelsCommand(): void
    {
        $this->call('ide-helper:models', [
            '--filename' => $this->filepath('models_filename'),
            '--write-mixin' => config('ide-helper-companion.write_mixins'),
        ]);
    }

    protected function ideHelperMetaCommand(): void
    {
        $this->call('ide-helper:meta', [
            '--filename' => $this->filepath('meta_filename')
        ]);
    }

    private function filepath(string $configIndex)
    {
        return config('ide-helper-companion.apply_base_directory')
            ? config('ide-helper-companion.base_directory').'/'.config("ide-helper.$configIndex")
            : config("ide-helper.$configIndex");
    }
}
