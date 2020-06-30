<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Exception;

use InvalidArgumentException;
use function Safe\sprintf;

final class TemplateNotFoundException extends InvalidArgumentException implements ExceptionInterface
{
    public function __construct(string $template, array $paths)
    {
        $message = sprintf('The template, "%s" was not found.', $template);

        if (count($paths) > 0) {
            $message .= sprintf(' Looked inside these paths (in this order): %s', implode(', ', $paths));
        } else {
            $message .= ' No paths has been added to the engine. Use the Setono\PhpTemplates\Engine\EngineInterface::addPath() method to do so.';
        }

        parent::__construct($message);
    }
}
