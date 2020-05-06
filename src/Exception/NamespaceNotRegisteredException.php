<?php

declare(strict_types=1);

namespace Setono\PhpTemplates\Exception;

use InvalidArgumentException;
use function Safe\sprintf;

final class NamespaceNotRegisteredException extends InvalidArgumentException implements ExceptionInterface
{
    public function __construct(string $namespace)
    {
        parent::__construct(sprintf('The namespace, "%s", is not registered', $namespace));
    }
}
