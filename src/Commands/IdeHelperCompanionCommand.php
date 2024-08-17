<?php

namespace michiruf\LaravelIdeHelperCompanion\Commands;

use Illuminate\Console\Command;

class IdeHelperCompanionCommand extends Command
{
    public $signature = 'ide-helper-companion';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
