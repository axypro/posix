<?php

declare(strict_types=1);

namespace axy\posix\exceptions;

use axy\posix\PosixErrors;
use LogicException;

class PosixException extends LogicException
{
    public readonly ?string $posixErrorConstant;
    public readonly string $posixErrorMessage;

    public function __construct(public readonly int $posixErrorCode)
    {
        $this->posixErrorConstant = PosixErrors::getConstName($posixErrorCode);
        $this->posixErrorMessage = (string)posix_strerror($posixErrorCode);
        $message = $this->posixErrorMessage;
        if ($this->posixErrorConstant !== null) {
            $message = "{$this->posixErrorConstant}: $message";
        }
        parent::__construct($message, $posixErrorCode);
    }
}
