<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Exception;

use InvalidArgumentException;
use function Safe\sprintf;

final class InvalidTemplateFormatException extends InvalidArgumentException implements ExceptionInterface
{
    public function __construct(string $template)
    {
        parent::__construct(sprintf(
            'The template, "%s", does not have the correct format. Use "@Namespace/template"', $template
        ));
    }
}
