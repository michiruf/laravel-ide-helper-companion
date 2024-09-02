<?php

use IdeHelperCompanion\Data\ClassDefinition;
use IdeHelperCompanion\Services\ModelProcessor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Workbench\App\Models\User;

use function Orchestra\Testbench\package_path;

beforeEach(function () {
    $gitVersion = Process::run('git --version');
    expect($gitVersion)->successful()->toBeTrue();
    $gitSafeDirectory = Process::run('git config --global --add safe.directory '.package_path());
    expect($gitSafeDirectory)->successful()->toBeTrue();
});

afterEach(function () {
    // Revert the user model only
    $gitRevertUserModel = Process::command('git checkout -- workbench/app/Models/User.php')
        ->path(package_path())
        ->run();
    expect($gitRevertUserModel)->successful()->toBeTrue();
});

it('can process models', function () {
    // Perform the processing
    $classDefinition = new ClassDefinition(User::class, package_path('workbench/app/Models/User.php'));
    $processor = app(ModelProcessor::class, [
        'definition' => $classDefinition,
    ]);
    $processor->process();

    // Expect annotations
    $file = File::get($classDefinition->filePath);
    expect($file)->toContainWithMessage('@property string $name', 'PHPDoc property for "name" nonexistent');
    $match = str($file)->match('|(/\*\*.*\*/)|us');
    expect($match)->toMatchSnapshot();
});
