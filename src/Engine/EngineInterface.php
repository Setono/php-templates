<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Engine;

interface EngineInterface
{
    /**
     * Paths are used to locate templates. The paths are ordered with the highest priority first.
     *
     * When rendering templates, the first path containing the template will be used
     *
     * NOTICE: If you add two paths with the same priority, it is not given that the path you add first will
     * be the one searched first because internally the array of paths uses the SplPriorityQueue
     */
    public function addPath(string $path, int $priority = 0): void;

    public function render(string $template, array $context = []): string;
}
