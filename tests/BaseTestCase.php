<?php

declare(strict_types=1);

namespace axy\posix\tests;

use axy\pkg\unit\BaseAxyTestCase;
use axy\posix\exceptions\PosixException;

abstract class BaseTestCase extends BaseAxyTestCase
{
    protected function expectPosixError(?int $code = null): void
    {
        $this->expectException(PosixException::class);
        if ($code !== null) {
            $this->expectExceptionCode($code);
        }
    }
}
