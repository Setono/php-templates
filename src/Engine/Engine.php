<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Engine;

use const E_USER_DEPRECATED;
use const PATHINFO_EXTENSION;
use function Safe\ob_end_clean;
use function Safe\preg_match;
use function Safe\sprintf;
use Setono\PhpTemplates\Exception\InvalidPathException;
use Setono\PhpTemplates\Exception\InvalidTemplateFormatException;
use Setono\PhpTemplates\Exception\RenderingException;
use Setono\PhpTemplates\Exception\TemplateNotFoundException;
use SplPriorityQueue;
use Throwable;

final class Engine implements EngineInterface
{
    /** @var string */
    private const TEMPLATE_FORMAT_REGEX = '^@([^/]+)/(.+\..+)';

    /** @var SplPriorityQueue */
    private $paths;

    public function __construct(array $paths = [])
    {
        $this->paths = new SplPriorityQueue();

        foreach ($paths as $idx => $value) {
            $priority = 0;
            $path = $value;

            // if the index is a string it is the namespace and the value is the priority
            if (is_string($idx)) {
                $path = $idx;
                $priority = $value;
            }
            $this->addPath($path, $priority);
        }
    }

    public function addPath(string $path, int $priority = 0): void
    {
        $path = rtrim($path, '/');

        if (!is_dir($path) || !is_readable($path)) {
            throw new InvalidPathException($path);
        }

        $this->paths->insert($path, $priority);
    }

    public function render(string $template, array $context = []): string
    {
        $template = $this->resolvePath($template);

        $inc = static function (): void {
            extract(func_get_arg(1));
            include func_get_arg(0);
        };

        return self::obWrap($template, static function () use ($inc, $template, $context): void {
            $inc($template, $context);
        });
    }

    private function resolvePath(string $template): string
    {
        // this is a BC layer for when a user render a template without an extension
        if (pathinfo($template, PATHINFO_EXTENSION) === '') {
            @trigger_error('Not adding an extension to the template name is deprecated since v1.1', E_USER_DEPRECATED);
            $template .= '.php';
        }

        if (preg_match(sprintf('#%s#', self::TEMPLATE_FORMAT_REGEX), $template, $matches) !== 1) {
            throw new InvalidTemplateFormatException($template);
        }

        [, $namespace, $filename] = $matches;

        $checkedPaths = [];

        foreach ($this->paths as $path) {
            $checkedPaths[] = $path;

            if (!is_dir($path . '/' . $namespace)) {
                continue;
            }

            $resolvedPath = $path . '/' . $namespace . '/' . $filename;
            if (!file_exists($resolvedPath)) {
                continue;
            }

            return $resolvedPath;
        }

        throw new TemplateNotFoundException($template, $checkedPaths);
    }

    private static function obWrap(string $template, callable $wrap): string
    {
        $level = ob_get_level();

        try {
            ob_start();
            $wrap();

            return (string) ob_get_clean();
        } catch (Throwable $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }

            throw new RenderingException($template, $e);
        }
    }
}
