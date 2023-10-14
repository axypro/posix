<?php

declare(strict_types=1);

namespace axy\posix\exceptions;

use LogicException;

class PosixNotImplementedException extends LogicException
{
    public function __construct(public readonly string $method)
    {
        parent::__construct("POSIX method $method is not implemented on this system");
    }
}
