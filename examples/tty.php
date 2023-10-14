<?php

declare(strict_types=1);

namespace axy\posix\examples;

use axy\posix\exceptions\PosixException;
use axy\posix\RealPosix;

include __DIR__ . '/../index.php';

$posix = new RealPosix();

echo "ctermid(): ";
try {
    echo $posix->ctermid() . "\n";
} catch (PosixException $e) {
    echo "{$e->getMessage()}\n";
}

echo "isatty(STDIN): " . ($posix->isatty(STDIN) ? 'True' : 'False') . "\n";

echo "ttyname(STDIN): ";
try {
    echo $posix->ttyname(STDIN) . "\n";
} catch (PosixException $e) {
    echo "{$e->getMessage()}\n";
}
