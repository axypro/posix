<?php

declare(strict_types=1);

namespace axy\posix\examples;

use axy\posix\exceptions\PosixException;
use axy\posix\RealPosix;

include __DIR__ . '/../index.php';

$posix = new RealPosix();

echo "uname(): ";
try {
    print_r($posix->uname()->data);
} catch (PosixException $e) {
    echo "{$e->getMessage()}\n";
}
echo "\n";

echo "times(): ";
try {
    print_r($posix->times()->data);
} catch (PosixException $e) {
    echo "{$e->getMessage()}\n";
}
echo "\n";

echo "ctermid(): ";
try {
    echo $posix->ctermid() . "\n";
} catch (PosixException $e) {
    echo "{$e->getMessage()}\n";
}

echo "getcwd(): ";
try {
    echo $posix->getcwd() . "\n";
} catch (PosixException $e) {
    echo "{$e->getMessage()}\n";
}
echo "\n";

echo "getrlimit():";
try {
    $limits = $posix->getrlimit();
    echo "\nhard: " . print_r($limits->hard->limits, true);
    echo "soft: " . print_r($limits->soft->limits, true);
} catch (PosixException $e) {
    echo "{$e->getMessage()}\n";
}

echo "\n";
