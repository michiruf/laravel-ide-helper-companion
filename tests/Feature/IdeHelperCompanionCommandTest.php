<?php

use Illuminate\Support\Facades\File;

it('can execute the command', function () {
    File::delete('_ide_helper.php');
    File::delete('_ide_helper_models.php');
    File::delete('.phpstorm.meta.php');

    File::deleteDirectory(config('ide-helper-companion.base_directory'));

    $this->artisan('ide-helper-companion')->assertOk();

    expect()
        ->and(File::exists('_ide_helper.php'))->toBeFalse()
        ->and(File::exists('_ide_helper_models.php'))->toBeFalse()
        ->and(File::exists('.phpstorm.meta.php'))->toBeFalse()
        ->and(File::exists(config('ide-helper-companion.base_directory').'/_ide_helper.php'))->toBeTrue()
        ->and(File::exists(config('ide-helper-companion.base_directory').'/_ide_helper_models.php'))->toBeTrue()
        ->and(File::exists(config('ide-helper-companion.base_directory').'/.phpstorm.meta.php'))->toBeTrue();

    $userModelContent = file_get_contents(base_path('../../../../workbench/app/Models/User.php'));
    expect($userModelContent)->toContain('@mixin IdeHelperUser');
});
