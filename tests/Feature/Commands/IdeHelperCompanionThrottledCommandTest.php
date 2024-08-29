<?php

use Illuminate\Support\Carbon;
use Symfony\Component\Console\Command\Command;

it('can execute the command ide-helper-companion:throttled', function () {
    $this->artisan('ide-helper-companion:throttled 10')->assertOk();
    Carbon::setTestNow(now()->subSeconds(10));
    $this->artisan('ide-helper-companion:throttled 10')->assertExitCode(Command::FAILURE);
});

it('can not execute the command ide-helper-companion:throttled with wrong arguments', function () {
    $this->artisan('ide-helper-companion:throttled abc')->assertExitCode(Command::INVALID);
});
