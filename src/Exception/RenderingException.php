<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Exception;

use RuntimeException;
use function Safe\sprintf;
use Throwable;

final class RenderingException extends RuntimeException implements ExceptionInterface
{
    public function __construct(string $template, Throwable $exception)
    {
        parent::__construct(sprintf('Exception thrown while rendering the template, %s', $template), 0, $exception);
    }
}
