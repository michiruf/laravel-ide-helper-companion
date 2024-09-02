<?php

namespace IdeHelperCompanion\Commands;

use IdeHelperCompanion\Commands\Helper\ThrottleCommandHelper;
use IdeHelperCompanion\Data\ClassDefinition;
use IdeHelperCompanion\Services\ModelDirectoryProcessor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class AnnotateCommand extends Command
{
    public $signature = 'ide-helper-companion:annotate {--D|dir=} {--throttle=0} {--pint}';

    public $description = 'Command to annotate model files';

    public function handle(ModelDirectoryProcessor $processor): int
    {
        $throttle = (int) ($this->option('throttle') ?? 0);

        return ThrottleCommandHelper::mayExecuteThrottled(
            'ide-helper-companion:annotate',
            $throttle,
            fn () => $this->handleNow($processor)
        );
    }

    protected function handleNow(ModelDirectoryProcessor $processor): int
    {
        $dir = $this->option('dir');
        if (! $dir) {
            $dir = app_path();
        }

        $processor
            ->loadClasses($dir)
            ->filterClasses()
            ->processModels();

        if ($this->option('pint')) {
            $classFilePaths = $processor->classes
                ->map(fn (ClassDefinition $definition) => $definition->filePath)
                ->join(' ');
            Process::path(base_path())->run("vendor/bin/pint $classFilePaths");
        }

        return self::SUCCESS;
    }
}
