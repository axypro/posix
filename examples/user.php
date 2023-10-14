<?php

declare(strict_types=1);

namespace axy\posix\examples;

use axy\posix\exceptions\PosixException;
use axy\posix\RealPosix;

include __DIR__ . '/../index.php';

$posix = new RealPosix();

echo "Real user: {$posix->getuid()}:{$posix->getgid()}\n";
echo "Effective user: {$posix->geteuid()}:{$posix->getegid()}\n";
echo "getlogin(): ";
try {
    echo $posix->getlogin();
} catch (PosixException $e) {
    echo $e->getMessage();
}
echo "\n";

echo "\nNow let's try get information about the user and the group\n";
echo "Success depended on privilege and docker\n\n";

echo "User: ";
$info = $posix->getpwuid($posix->getuid());
if ($info !== null) {
    print_r($info->data);
} else {
    echo "undefined, let's try the root info:";
    $info = $posix->getpwuid(0);
    if ($info !== null) {
        print_r($info->data);
    } else {
        echo "\n";
    }
}

echo "\nGroup: ";
$info = $posix->getgrgid($posix->getgid());
if ($info !== null) {
    print_r($info->data);
} else {
    echo "undefined, let's try the root info:";
    $info = $posix->getgrgid(0);
    if ($info !== null) {
        print_r($info->data);
    } else {
        echo "\n";
    }
}

echo "\nFinally, let's change IDs:\n";
$id = 76;
foreach (['setgid', 'setegid', 'setuid', 'seteuid'] as $method) {
    $id++;
    echo "$method($id): ";
    try {
        $posix->$method($id);
        echo 'success';
    } catch (PosixException $e) {
        echo $e->getMessage();
    }
    echo "\n";
}

echo "Real user: {$posix->getuid()}:{$posix->getgid()}\n";
echo "Effective user: {$posix->geteuid()}:{$posix->getegid()}\n";
