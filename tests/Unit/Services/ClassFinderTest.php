<?php


use IdeHelperCompanion\Data\ClassDefinition;
use IdeHelperCompanion\Services\ClassFinder;
use Illuminate\Support\Collection;

it('can find classes in a directory', function () {
    $processor = app(ClassFinder::class);
    $classes = $processor->findClassesInDirectory(__DIR__.'/../../../workbench');
    expect($classes)->toBeInstanceOf(Collection::class)
        ->get(0)->toBeInstanceOf(ClassDefinition::class);
});
