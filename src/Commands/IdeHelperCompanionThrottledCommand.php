<?php

namespace IdeHelperCompanion\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class IdeHelperCompanionThrottledCommand extends Command
{
    public $signature = 'ide-helper-companion:throttled {throttle=2}';

    public $description = 'Command to generate IDE helper files and throttle execution by time in seconds';

    public function handle(): int
    {
        $throttle = (int) $this->argument('throttle');
        if ($throttle == 0) {
            return self::INVALID;
        }

        $lock = Cache::lock('ide-helper-companion:throttled', $throttle);

        if ($lock->get()) {
            $this->call('ide-helper-companion');
            return self::SUCCESS;
        }

        Carbon::sleep($throttle);

        if ($lock->get()) {
            $this->call('ide-helper-companion');
            return self::SUCCESS;
        }

        return self::FAILURE;
    }
}
