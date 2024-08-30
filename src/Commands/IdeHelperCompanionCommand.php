<?php

namespace IdeHelperCompanion\Commands;

use IdeHelperCompanion\Commands\Helper\ThrottleCommandHelper;
use Illuminate\Console\Command;

class IdeHelperCompanionCommand extends Command
{
    public $signature = 'ide-helper-companion {--throttle=0}';

    public $description = 'Command to perform all ide helper companion commands';

    public function handle(): int
    {
        $throttle = (int) ($this->option('throttle') ?? 0);

        return ThrottleCommandHelper::mayExecuteThrottled(
            'ide-helper-companion',
            $throttle,
            fn () => $this->handleNow()
        );
    }

    protected function handleNow(): int
    {
        return max(
            $this->call('ide-helper-companion:annotate', ['--throttle' => 0]),
            $this->call('ide-helper-companion:generate', ['--throttle' => 0])
        );
    }
}
