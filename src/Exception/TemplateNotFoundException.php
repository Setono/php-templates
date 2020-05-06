<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Exception;

use InvalidArgumentException;
use function Safe\sprintf;

final class TemplateNotFoundException extends InvalidArgumentException implements ExceptionInterface
{
    public function __construct(string $template, string $path)
    {
        parent::__construct(sprintf(
            'The template, "%s" was not found. Looked inside this directory: %s', $template, $path
        ));
    }
}
