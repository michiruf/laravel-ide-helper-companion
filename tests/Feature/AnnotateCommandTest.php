<?php

use Workbench\App\Models\User;

it('can execute the command', function () {
    // Action
    $this->artisan('ide-helper-companion:annotate')->assertOk();

    // Assert: annotated files
});
