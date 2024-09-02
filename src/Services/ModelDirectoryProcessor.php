<?php

namespace IdeHelperCompanion\Services;

use IdeHelperCompanion\Data\ClassDefinition;
use Illuminate\Support\Collection;

class ModelDirectoryProcessor
{
    private ClassFinder $classFinder;

    /**
     * @param  ?Collection<int, ClassDefinition>  $classes
     */
    public function __construct(
        public ?Collection $classes = null
    ) {}

    /**
     * @param  string|string[]  $paths
     */
    public function loadClasses(string|array $paths): static
    {
        $this->classes()->push(
            ...$this->classFinder()->findClassesInDirectory($paths)
        );

        return $this;
    }

    public function filterClasses(): static
    {
        $this->classes = $this->classes()
            ->filter(fn (ClassDefinition $definition) => $definition->classExists() && $definition->isInstantiable() && $definition->isModel());

        return $this;
    }

    public function processModels(): void
    {
        $this->classes()->each(fn (ClassDefinition $definition) => app(ModelProcessor::class, ['definition' => $definition])->process());
    }

    protected function classFinder(): ClassFinder
    {
        return $this->classFinder ??= app(ClassFinder::class);
    }

    protected function classes(): Collection
    {
        return $this->classes ??= Collection::wrap($this->classes);
    }
}
