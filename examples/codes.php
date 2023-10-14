<?php

declare(strict_types=1);

namespace axy\posix\examples;

use axy\posix\RealPosix;

include __DIR__ . '/../index.php';

$posix = new RealPosix();

echo "Error codes:\n";
for ($i = -1; $i <= 134; $i++) {
    echo "$i: {$posix->strerror($i)}\n";
}
