<?php

declare(strict_types=1);

namespace axy\posix;

use axy\posix\exceptions\PosixException;

class PosixListener implements IPosixListener
{
    public function before(string $method, array $args, ?int &$code = null): mixed
    {
        return null;
    }

    public function after(string $method, array $args, mixed $result, ?int &$code = null): mixed
    {
        return $result;
    }

    protected function error(int $code): never
    {
        throw new PosixException($code);
    }
}
