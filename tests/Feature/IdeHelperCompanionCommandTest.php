<?php

use Illuminate\Support\Facades\File;
use Illuminate\Process\Factory as Process;

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

    // Assert: user model was annotated
    $userModelContent = file_get_contents(base_path('../../../../workbench/app/Models/User.php'));
    expect($userModelContent)->toContain('@mixin IdeHelperUser');

    // Setup: revert the user model
    $gitVersion = (new Process())->run('git --version');
    expect($gitVersion)->successful()->toBeTrue($gitVersion->output());
    $gitSafeDirectory =  (new Process())->run('git config --global --add safe.directory /var/www/html');
    expect($gitSafeDirectory)->successful()->toBeTrue($gitSafeDirectory->output());
    $gitRevertUserModel =  (new Process())->command('git checkout -- workbench/app/Models/User.php')
        ->path(base_path('../../../../'))
        ->run();
    expect($gitRevertUserModel)->successful()->toBeTrue($gitRevertUserModel->output());

    // Assert: user model does not contain the generated mixin annotation
    $userModelContent = file_get_contents(base_path('../../../../workbench/app/Models/User.php'));
    expect($userModelContent)->not->toContain('@mixin IdeHelperUser');
});
