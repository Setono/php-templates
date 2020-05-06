<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Engine;

interface EngineInterface
{
    /**
     * Paths are used to locate templates. The namespace is used to distinguish between templates with the same name
     */
    public function addPath(string $namespace, string $path): void;

    public function render(string $template, array $context = []): string;
}
