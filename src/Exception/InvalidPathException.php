<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Exception;

use InvalidArgumentException;
use function Safe\sprintf;

final class InvalidPathException extends InvalidArgumentException implements ExceptionInterface
{
    public function __construct(string $path)
    {
        parent::__construct(sprintf('The path, "%s", does not exist or is not readable', $path));
    }
}
