<?php

declare(strict_types=1);

namespace axy\posix\examples;

use axy\posix\PosixConstants;
use axy\posix\RealPosix;

include __DIR__ . '/../index.php';

$posix = new RealPosix();

$access = [
    ['Exists', PosixConstants::X_OK],
    ['Read', PosixConstants::R_OK],
    ['Write', PosixConstants::W_OK],
    ['Exec', PosixConstants::X_OK],
    ['Read+Write', PosixConstants::R_OK | PosixConstants::W_OK],
    ['Read+Exec', PosixConstants::R_OK | PosixConstants::X_OK],
];

$files = [
    ['The current file', __FILE__],
    ['Shell file', __DIR__ . '/run.sh'],
    ['Non-existent file', __DIR__ . '/non-existent.txt'],
];

foreach ($files as [$fileTitle, $filePath]) {
    foreach ($access as [$accessTitle, $accessConst]) {
        $result = $posix->access($filePath, $accessConst) ? 'Yes' : 'No';
        echo "$fileTitle ($accessTitle): $result\n";
    }
    echo "\n";
}
