<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Exception;

use InvalidArgumentException;
use function Safe\sprintf;

final class TemplateNotFoundException extends InvalidArgumentException implements ExceptionInterface
{
    public function __construct(string $template, array $paths)
    {
        parent::__construct(sprintf(
            'The template, "%s" was not found. Looked inside these paths (in this order): %s', $template, implode(', ', $paths)
        ));
    }
}
