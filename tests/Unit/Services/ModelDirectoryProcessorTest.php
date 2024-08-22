<?php

use IdeHelperCompanion\Data\ClassDefinition;
use IdeHelperCompanion\Services\ModelDirectoryProcessor;
use IdeHelperCompanion\Services\ModelProcessor;
use Workbench\App\Models\User;
use Workbench\App\Providers\WorkbenchServiceProvider;

it('can filter models', function () {
    $processor = app(ModelDirectoryProcessor::class);

    // Model passes filter
    $processor->classes = collect([
        new ClassDefinition(User::class, base_path('workbench/app/Models/User.php')),
    ]);
    $processor->filterClasses();
    expect($processor->classes)->toHaveCount(1);

    // Non-model not passes filter
    $processor->classes = collect([
        new ClassDefinition(WorkbenchServiceProvider::class, base_path('workbench/app/Providers/WorkbenchServiceProvider.php')),
    ]);
    $processor->filterClasses();
    expect($processor->classes)->toHaveCount(0);
});

it('will try to execute the model process', function () {
    mockAndBind(ModelProcessor::class)
        ->shouldReceive('process')
        ->once();

    app(ModelDirectoryProcessor::class, [
        'classes' => collect([
            new ClassDefinition(User::class, base_path('workbench/app/Models/User.php')),
        ])
    ])
        ->filterClasses()
        ->processModels();
});
