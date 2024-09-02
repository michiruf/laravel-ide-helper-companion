<?php

namespace IdeHelperCompanion\Data;

use ReflectionClass;
use ReflectionException;
use RuntimeException;

// TODO Maybe add a wrapper object that caches all values and can be reset when specific properties are accessed

class ClassDefinition
{
    protected ReflectionClass $reflectionClass;

    public function __construct(
        /** @var class-string $classString */
        public string $classString,
        public string $filePath,
    ) {}

    public function classExists(): bool
    {
        return class_exists($this->classString);
    }

    public function isInstantiable(): bool
    {
        return $this->reflection()->isInstantiable();
    }

    public function isModel(): bool
    {
        return $this->reflection()->isSubclassOf('Illuminate\Database\Eloquent\Model');
    }

    public function reflection(): ReflectionClass
    {
        try {
            return $this->reflectionClass ??= new ReflectionClass($this->classString);
        } catch (ReflectionException $e) {
            throw new RuntimeException($e);
        }
    }
}
