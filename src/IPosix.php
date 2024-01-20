<?php

declare(strict_types=1);

namespace axy\posix;

use axy\posix\exceptions\PosixException;

interface IPosix
{
    /** @see posix_access() */
    public function access(string $filename, int $flags = PosixConstants::F_OK): bool;

    /**
     * @see posix_ctermid()
     * @throws PosixException
     */
    public function ctermid(): string;

    /** @see posix_eaccess */
    public function eaccess(string $filename, int $flags = PosixConstants::F_OK): bool;

    /**
     * @see posix_fpathconf
     * @throws PosixException
     */
    public function fpathconf(mixed $fileDescription, int $name): int;

    /**
     * @see posix_getcwd()
     * @throws PosixException
     */
    public function getcwd(): string;

    /** @see posix_getegid() */
    public function getegid(): int;

    /** @see posix_geteuid() */
    public function geteuid(): int;

    /** @see posix_getgid() */
    public function getgid(): int;

    /** @see posix_getgrgid() */
    public function getgrgid(int $gid): ?PosixGroupInfo;

    /** @see posix_getgrnam() */
    public function getgrnam(string $groupName): ?PosixGroupInfo;

    /**
     * @see posix_getgroups
     * @throws PosixException
     */
    public function getgroups(): array;

    /**
     * @see posix_getlogin()
     * @throws PosixException
     */
    public function getlogin(): string;

    /**
     * @see posix_getpgid()
     * @throws PosixException
     */
    public function getpgid(int $processId): int;

    /** @see posix_getpgrp() */
    public function getpgrp(): int;

    /** @see posix_getpid() */
    public function getpid(): int;

    /** @see posix_getppid() */
    public function getppid(): int;

    /** @see posix_getpwnam() */
    public function getpwnam(string $userName): ?PosixUserInfo;

    /** @see posix_getpwuid() */
    public function getpwuid(int $userId): ?PosixUserInfo;

    /**
     * @see posix_getrlimit()
     * @throws PosixException
     */
    public function getrlimit(): PosixResourceLimits;

    /**
     * @see posix_getsid()
     * @throws PosixException
     */
    public function getsid(int $processId): int;

    /** @see posix_getuid() */
    public function getuid(): int;

    /**
     * @see posix_initgroups()
     * @throws PosixException
     */
    public function initgroups(string $userName, int $groupId): void;

    /** @see posix_isatty() */
    public function isatty(mixed $fileDescriptor): bool;

    /**
     * @see posix_kill()
     * @throws PosixException
     */
    public function kill(int $processId, int $signal): void;

    /**
     * @see posix_mkfifo
     * @throws PosixException
     */
    public function mkfifo(string $filename, int $permissions): void;

    /**
     * @see posix_mknod()
     * @throws PosixException
     */
    public function mknod(string $filename, int $flags, int $major = 0, int $minor = 0): void;

    /**
     * @see posix_setegid()
     * @throws PosixException
     */
    public function setegid(int $groupId): void;

    /**
     * @see posix_seteuid()
     * @throws PosixException
     */
    public function seteuid(int $userId): void;

    /**
     * @see posix_setgid
     * @throws PosixException
     */
    public function setgid(int $groupId): void;

    /**
     * @see posix_setpgid()
     * @throws PosixException
     */
    public function setpgid(int $processId, int $processGroupId): void;

    /**
     * @see posix_setrlimit()
     * @throws PosixException
     */
    public function setrlimit(int $resource, int $softLimit, int $hardLimit): void;

    /** @see posix_setsid() */
    public function setsid(): int;

    /** @see posix_setuid() */
    public function setuid(int $userId): void;

    /** @see posix_strerror() */
    public function strerror(int $code): string;

    /** @see posix_sysconf */
    public function sysconf(int $confId): int;

    /**
     * @see posix_times()
     * @throws PosixException
     */
    public function times(): PosixTimesInfo;

    /**
     * @see posix_ttyname()
     * @throws PosixException
     */
    public function ttyname(mixed $fileDescriptor): string;

    /**
     * @see posix_uname()
     * @throws PosixException
     */
    public function uname(): PosixUNameInfo;
}
