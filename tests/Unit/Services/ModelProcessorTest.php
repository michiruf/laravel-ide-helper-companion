<?php

use IdeHelperCompanion\Data\ClassDefinition;
use IdeHelperCompanion\Services\ModelProcessor;
use Workbench\App\Models\User;

it('can process models', function () {
    $processor = app(ModelProcessor::class, [
        'definition' => new ClassDefinition(User::class, base_path('workbench/app/Models/User.php'))
    ]);
    $processor->process();
});
