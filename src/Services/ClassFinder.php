<?php

namespace IdeHelperCompanion\Services;

use Composer\ClassMapGenerator\ClassMapGenerator;
use IdeHelperCompanion\Data\ClassDefinition;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ClassFinder
{
    /**
     * @param  string|string[]  $paths
     * @return Collection<int, ClassDefinition>
     */
    public function findClassesInDirectory(string|array $paths): Collection
    {
        $result = collect();

        $paths = Arr::wrap($paths);
        foreach ($paths as $path) {
            $classMap = ClassMapGenerator::createMap($path);
            ksort($classMap);
            $result->push(
                ...Arr::map($classMap, fn (string $path, string $class) => new ClassDefinition($class, $path))
            );
        }

        return $result;
    }
}
