<?php

namespace IdeHelperCompanion\Commands;

use IdeHelperCompanion\Commands\Helper\ThrottleCommandHelper;
use IdeHelperCompanion\Services\ModelDirectoryProcessor;
use Illuminate\Console\Command;

class AnnotateCommand extends Command
{
    public $signature = 'ide-helper-companion:annotate {--D|dir=} {--throttle=0}';

    public $description = 'Command to annotate model files';

    public function handle(ModelDirectoryProcessor $processor): int
    {
        $throttle = (int) ($this->option('throttle') ?? 0);

        return ThrottleCommandHelper::mayExecuteThrottled(
            'ide-helper-companion',
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

        return self::SUCCESS;
    }
}
