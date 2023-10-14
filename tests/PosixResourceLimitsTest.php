<?php

declare(strict_types=1);

namespace axy\posix\tests;

use axy\posix\PosixResourceLimits;

class PosixResourceLimitsTest extends BaseTestCase
{
    public function testResources(): void
    {
        $data = [
            'soft core' => 'unlimited',
            'hard core' => 12345,
            'undefined' => 123,
            'hard undefined' => 456,
            'hard cpu' => 'undefined',
        ];
        $limits = new PosixResourceLimits($data);
        $this->assertSame($data, $limits->data);
        $this->assertEquals([
            'core' => 'unlimited',
        ], $limits->soft->limits);
        $this->assertEquals([
            'core' => 12345,
            'undefined' => 456,
            'cpu' => 'undefined',
        ], $limits->hard->limits);
        $this->assertSame('unlimited', $limits->soft->core);
        $this->assertSame(12345, $limits->hard->core);
        $this->assertSame('', $limits->soft->cpu);
        $this->assertSame('undefined', $limits->hard->cpu);
    }
}
