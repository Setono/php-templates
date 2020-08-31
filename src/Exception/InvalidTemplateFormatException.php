<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Exception;

use InvalidArgumentException;
use function Safe\sprintf;

final class InvalidTemplateFormatException extends InvalidArgumentException implements ExceptionInterface
{
    public function __construct(string $template, string $regexToMatch = null)
    {
        $message = sprintf(
            'The template, "%s", does not have the correct format.', $template
        );

        if (null === $regexToMatch) {
            $message .= ' Use "@Namespace/template.ext"';
        } else {
            $message .= sprintf(' The format must match this regex: %s', $regexToMatch);
        }

        parent::__construct($message);
    }
}
