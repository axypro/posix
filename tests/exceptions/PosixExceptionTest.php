<?php

declare(strict_types=1);

namespace axy\posix\tests\exceptions;

use axy\posix\exceptions\PosixException;
use axy\posix\tests\BaseTestCase;

class PosixExceptionTest extends BaseTestCase
{
    public function testMessage(): void
    {
        $e = new PosixException(5);
        $this->assertSame(5, $e->getCode());
        $this->assertSame(posix_strerror(5), $e->getMessage());
    }
}
