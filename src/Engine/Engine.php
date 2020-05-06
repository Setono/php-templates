<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Engine;

use function Safe\ob_end_clean;
use function Safe\preg_match;
use Setono\PhpTemplates\Exception\ExistingNamespaceException;
use Setono\PhpTemplates\Exception\InvalidTemplateFormatException;
use Setono\PhpTemplates\Exception\NamespaceNotRegisteredException;
use Setono\PhpTemplates\Exception\RenderingException;
use Setono\PhpTemplates\Exception\TemplateNotFoundException;
use Throwable;

final class Engine implements EngineInterface
{
    /** @var array */
    private $paths = [];

    public function __construct(array $paths = [])
    {
        foreach ($paths as $namespace => $path) {
            $this->addPath($namespace, $path);
        }
    }

    public function addPath(string $namespace, string $path): void
    {
        if (isset($this->paths[$namespace])) {
            throw new ExistingNamespaceException($namespace);
        }

        $this->paths[$namespace] = rtrim($path, '/');
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
        if (preg_match('#^@([^/]+)/(.+)#', $template, $matches) !== 1) {
            throw new InvalidTemplateFormatException($template);
        }

        $namespace = $matches[1];
        $filename = $matches[2] . '.php';

        if (!isset($this->paths[$namespace])) {
            throw new NamespaceNotRegisteredException($namespace);
        }

        $path = $this->paths[$namespace] . '/' . $filename;

        if (!file_exists($path)) {
            throw new TemplateNotFoundException($template, $this->paths[$namespace]);
        }

        return $path;
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
