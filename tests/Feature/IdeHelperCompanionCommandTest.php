<?php

use Illuminate\Support\Facades\File;

it('can execute the command', function () {
    // Setup: clear previous generated stuff
    File::deleteDirectory(config('ide-helper-companion.base_directory'));

    // Action
    $this->artisan('ide-helper-companion')->assertOk();

    // Assert: created files
    expect()
        ->and(File::exists('_ide_helper.php'))->toBeFalse()
        ->and(File::exists('_ide_helper_models.php'))->toBeFalse()
        ->and(File::exists('.phpstorm.meta.php'))->toBeFalse()
        ->and(File::exists(config('ide-helper-companion.base_directory').'/_ide_helper.php'))->toBeTrue()
        ->and(File::exists(config('ide-helper-companion.base_directory').'/_ide_helper_models.php'))->toBeTrue()
        ->and(File::exists(config('ide-helper-companion.base_directory').'/.phpstorm.meta.php'))->toBeTrue();
});
