<?php


use IdeHelperCompanion\Data\ClassDefinition;
use IdeHelperCompanion\Services\ClassFinder;
use Illuminate\Support\Collection;
use function Orchestra\Testbench\package_path;

it('can find classes in a directory', function () {
    $processor = app(ClassFinder::class);
    $classes = $processor->findClassesInDirectory(package_path('workbench'));
    expect($classes)->toBeInstanceOf(Collection::class)
        ->get(0)->toBeInstanceOf(ClassDefinition::class);
});
