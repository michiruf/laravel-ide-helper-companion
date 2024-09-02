<?php

use IdeHelperCompanion\Commands\Helper\ThrottleCommandHelper;
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Command\Command;

it('can throttle commands', function () {
    expect(ThrottleCommandHelper::mayExecuteThrottled('test', 10, fn () => Command::SUCCESS))->toBe(Command::SUCCESS);
    Carbon::setTestNow(now()->subSeconds(10));
    expect(ThrottleCommandHelper::mayExecuteThrottled('test', 10, fn () => Command::SUCCESS))->toBe(Command::FAILURE);
});
