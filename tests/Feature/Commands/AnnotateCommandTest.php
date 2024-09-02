<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use function Orchestra\Testbench\package_path;

beforeEach(function () {
    $gitVersion = Process::run('git --version');
    expect($gitVersion)->successful()->toBeTrue();
    $gitSafeDirectory = Process::run('git config --global --add safe.directory '.package_path());
    expect($gitSafeDirectory)->successful()->toBeTrue();
});

afterEach(function () {
    // Revert the models
    $gitRevertUserModel = Process::command('git checkout -- workbench/app/Models')
        ->path(package_path())
        ->run();
    expect($gitRevertUserModel)->successful()->toBeTrue();
});

it('can execute the command', function ($model) {
    // Action
    $this->artisan('ide-helper-companion:annotate --dir=workbench')->assertOk();

    // Expect annotations
    $file = File::get(package_path("workbench/app/Models/$model.php"));
    expect($file)->toContainWithMessage('@property string $name', 'PHPDoc property for "name" nonexistent');
    $match = str($file)->match('|(/\*\*.*\*/)|us');
    expect($match)->toMatchSnapshot();
})->with(['User', 'Product']);

it('can execute with pint', function ($model) {
    // Action
    $this->artisan('ide-helper-companion:annotate --dir=workbench --pint')->assertOk();

    // Expect annotations
    $file = File::get(package_path("workbench/app/Models/$model.php"));
    expect($file)->toContainWithMessage('@property string $name', 'PHPDoc property for "name" nonexistent');
    $match = str($file)->match('|(/\*\*.*\*/)|us');
    expect($match)->toMatchSnapshot();
})->with(['User', 'Product']);
