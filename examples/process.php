<?php

declare(strict_types=1);

namespace axy\posix\examples;

use axy\posix\exceptions\{
    PosixException,
    PosixNotImplementedException,
};
use axy\posix\RealPosix;

include __DIR__ . '/../index.php';

$posix = new RealPosix();

echo "PID = {$posix->getpid()}\n";
echo "Group = {$posix->getpgrp()}\n";
try {
    echo "Parent PID = {$posix->getppid()}\n";
} catch (PosixNotImplementedException $e) {
    echo "{$e->getMessage()}\n";
}
try {
    echo "SID = {$posix->getsid(0)}\n";
    echo "Parent SID = {$posix->getsid($posix->getppid())}\n";
} catch (PosixException $e) {
    echo "{$e->getMessage()}\n";
}
