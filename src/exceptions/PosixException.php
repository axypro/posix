<?php

declare(strict_types=1);

namespace axy\posix\exceptions;

use LogicException;

class PosixException extends LogicException
{
    public function __construct(int $code)
    {
        parent::__construct((string)posix_strerror($code), $code);
    }
}
