<?php

declare(strict_types=1);

namespace axy\posix\tests;

use axy\posix\{
    PosixConstants,
    PosixListener,
    RealPosix,
};
use axy\posix\exceptions\PosixException;

class PosixListenerTest extends BaseTestCase
{
    public function testListener(): void
    {
        $listener = new class () extends PosixListener {
            public function before(string $method, array $args, ?int &$code = null): mixed
            {
                if ($method === 'getgrgid') {
                    if ($args[0] === 5) {
                        return [
                            'id' => $args[0],
                            'name' => 'before',
                        ];
                    }
                    return false;
                }
                return parent::before($method, $args, $code);
            }

            public function after(string $method, array $args, mixed $result, ?int &$code = null): mixed
            {
                if ($method === 'getgrgid') {
                    if (is_array($result)) {
                        $result['name'] .= ' after';
                    }
                }
                return parent::after($method, $args, $result, $code);
            }
        };
        $posix = new RealPosix($listener);
        $this->assertPartiallySame([
            'id' => 5,
            'name' => 'before after',
        ], $posix->getgrgid(5)->data);
        $this->assertNull($posix->getgrgid(6));
        $this->assertSame(posix_getuid(), $posix->getuid());
    }

    public function testError(): void
    {
        $listener = new class () extends PosixListener {
            public function before(string $method, array $args, ?int &$code = null): mixed
            {
                switch ($method) {
                    case 'ctermid':
                        $code = 5;
                        return false;
                    case 'getcwd':
                        return '/app';
                    case 'access':
                        if ($args[1] === PosixConstants::W_OK) {
                            $this->error(5);
                        }
                        break;
                }
                return parent::before($method, $args, $code);
            }

            public function after(string $method, array $args, mixed $result, ?int &$code = null): mixed
            {
                $this->lastCode = $code;
                if ($code === 5) {
                    $code = 4;
                }
                if ($method === 'getcwd') {
                    $code = 3;
                    return false;
                }
                return parent::after($method, $args, $result, $code);
            }

            public ?int $lastCode = null;
        };
        $posix = new RealPosix($listener);

        try {
            $posix->ctermid();
            $this->fail('Not thrown');
        } catch (PosixException $e) {
            $this->assertSame(4, $e->getCode());
            $this->assertSame(5, $listener->lastCode);
        }
        try {
            $posix->getcwd();
            $this->fail('Not thrown');
        } catch (PosixException $e) {
            $this->assertSame(3, $e->getCode());
        }
        $listener->lastCode = null;
        $this->assertTrue($posix->access(__FILE__, PosixConstants::R_OK));
        try {
            $posix->access(__FILE__, PosixConstants::W_OK);
            $this->fail('Not thrown');
        } catch (PosixException $e) {
            $this->assertSame(5, $e->getCode());
            $this->assertNull($listener->lastCode);
        }
    }
}
