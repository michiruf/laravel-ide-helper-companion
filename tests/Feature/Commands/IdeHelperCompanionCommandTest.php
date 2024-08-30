<?php

use Illuminate\Support\Carbon;
use Symfony\Component\Console\Command\Command;

it('can execute the command ide-helper-companion throttled', function () {
    $this->artisan('ide-helper-companion --throttle=10')->assertOk();
    Carbon::setTestNow(now()->subSeconds(10));
    $this->artisan('ide-helper-companion --throttle=10')->assertExitCode(Command::FAILURE);
});
