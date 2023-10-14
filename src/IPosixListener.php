<?php

declare(strict_types=1);

namespace axy\posix;

interface IPosixListener
{
    public function before(string $method, array $args, ?int &$code = null): mixed;

    public function after(string $method, array $args, mixed $result, ?int &$code = null): mixed;
}
