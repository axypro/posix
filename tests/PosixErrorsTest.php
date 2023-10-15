<?php

declare(strict_types=1);

namespace axy\posix\tests;

use axy\posix\PosixErrors;

class PosixErrorsTest extends BaseTestCase
{
    public function testGetConstName(): void
    {
        $this->assertSame('EDESTADDRREQ', PosixErrors::getConstName(PosixErrors::EDESTADDRREQ));
        $this->assertSame('EADV', PosixErrors::getConstName(PosixErrors::EADV));
        $this->assertNull(PosixErrors::getConstName(-5));
        $this->assertNull(PosixErrors::getConstName(500));
    }
}
