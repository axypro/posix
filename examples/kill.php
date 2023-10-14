<?php

declare(strict_types=1);

namespace axy\posix\examples;

use axy\posix\RealPosix;

include __DIR__ . '/../index.php';

$posix = new RealPosix();

$pid = $posix->getpid();

for ($i = 0; $i < 3; $i++) {
    sleep(1);
    echo "I am still alive\n";
    $posix->kill($pid, 15);
}
