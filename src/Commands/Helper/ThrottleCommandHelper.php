<?php

namespace IdeHelperCompanion\Commands\Helper;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Console\Command\Command;

class ThrottleCommandHelper
{
    public static function mayExecuteThrottled(string $identifier, int $throttle, callable $callback): int
    {
        if ($throttle === 0) {
            return $callback();
        }

        return static::executeThrottled($identifier, $throttle, $callback);
    }

    public static function executeThrottled(string $identifier, int $throttle, callable $callback): int
    {
        if ($throttle == 0) {
            return Command::INVALID;
        }

        $lock = Cache::lock($identifier, $throttle);

        if ($lock->get()) {
            return $callback();
        }

        Carbon::sleep($throttle);

        if ($lock->get()) {
            return $callback();
        }

        return Command::FAILURE;
    }
}
