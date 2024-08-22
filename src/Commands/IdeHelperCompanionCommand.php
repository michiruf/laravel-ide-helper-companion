<?php

namespace IdeHelperCompanion\Commands;

use Illuminate\Console\Command;

class IdeHelperCompanionCommand extends Command
{
    public $signature = 'ide-helper-companion';

    public $description = 'Command to perform all ide helper companion commands';

    public function handle(): int
    {
        $this->call('ide-helper-companion:annotate');
        $this->call('ide-helper-companion:generate');

        return self::SUCCESS;
    }
}
