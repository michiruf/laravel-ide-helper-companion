<?php

it('can execute the command', function () {
    $this->artisan('ide-helper-companion')->assertOk();
});
