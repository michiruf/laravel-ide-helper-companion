<?php

namespace IdeHelperCompanion\Commands;

use IdeHelperCompanion\Services\ModelDirectoryProcessor;
use Illuminate\Console\Command;

class AnnotateCommand extends Command
{
    public $signature = 'ide-helper-companion:annotate {--D|dir=}';

    public $description = 'Command to annotate model files';

    public function handle(ModelDirectoryProcessor $processor): int
    {
        $dir = $this->option('dir');
        if (! $dir) {
            $dir = app_path();
        }

        $processor
            ->loadClasses($dir)
            ->filterClasses()
            ->processModels();

        return self::SUCCESS;
    }
}
