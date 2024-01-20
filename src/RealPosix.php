<?php

declare(strict_types=1);

namespace axy\posix;

use axy\posix\exceptions\{
    PosixException,
    PosixNotImplementedException,
};

class RealPosix implements IPosix
{
    public function __construct(public readonly ?IPosixListener $listener = null)
    {
    }

    public function access(string $filename, int $flags = PosixConstants::F_OK): bool
    {
        return $this->runMethod(__FUNCTION__, [$filename, $flags]);
    }

    public function ctermid(): string
    {
        return $this->runMethod(__FUNCTION__, [], false);
    }

    public function eaccess(string $filename, int $flags = PosixConstants::F_OK): bool
    {
        return $this->runMethod(__FUNCTION__, [$filename, $flags]);
    }

    public function fpathconf(mixed $fileDescription, int $name): int
    {
        return $this->runMethod(__FUNCTION__, [$fileDescription, $name], false);
    }

    public function getcwd(): string
    {
        return $this->runMethod(__FUNCTION__, [], false);
    }

    public function getegid(): int
    {
        return $this->runMethod(__FUNCTION__);
    }

    public function geteuid(): int
    {
        return $this->runMethod(__FUNCTION__);
    }

    public function getgid(): int
    {
        return $this->runMethod(__FUNCTION__);
    }

    public function getgrgid(int $gid): ?PosixGroupInfo
    {
        return $this->createGroupInfo($this->runMethod(__FUNCTION__, [$gid]));
    }

    public function getgrnam(string $groupName): ?PosixGroupInfo
    {
        return $this->createGroupInfo($this->runMethod(__FUNCTION__, [$groupName]));
    }

    public function getgroups(): array
    {
        return $this->runMethod(__FUNCTION__, [], false);
    }

    public function getlogin(): string
    {
        return $this->runMethod(__FUNCTION__, [], false);
    }

    public function getpgid(int $processId): int
    {
        $this->checkImplementation(__FUNCTION__);
        return $this->runMethod(__FUNCTION__, [$processId], false);
    }

    public function getpgrp(): int
    {
        return $this->runMethod(__FUNCTION__);
    }

    public function getpid(): int
    {
        return $this->runMethod(__FUNCTION__);
    }

    public function getppid(): int
    {
        return $this->runMethod(__FUNCTION__);
    }

    public function getpwnam(string $userName): ?PosixUserInfo
    {
        return $this->createUserInfo($this->runMethod(__FUNCTION__, [$userName]));
    }

    public function getpwuid(int $userId): ?PosixUserInfo
    {
        return $this->createUserInfo($this->runMethod(__FUNCTION__, [$userId]));
    }

    public function getrlimit(): PosixResourceLimits
    {
        return new PosixResourceLimits($this->runMethod(__FUNCTION__, [], false));
    }

    public function getsid(int $processId): int
    {
        return $this->runMethod(__FUNCTION__, [$processId], false);
    }

    public function getuid(): int
    {
        return $this->runMethod(__FUNCTION__);
    }

    public function initgroups(string $userName, int $groupId): void
    {
        $this->runMethod(__FUNCTION__, [$userName, $groupId], false);
    }

    public function isatty(mixed $fileDescriptor): bool
    {
        return $this->runMethod(__FUNCTION__, [$fileDescriptor]);
    }

    public function kill(int $processId, int $signal): void
    {
        $this->runMethod(__FUNCTION__, [$processId, $signal], false);
    }

    public function mkfifo(string $filename, int $permissions): void
    {
        $this->runMethod(__FUNCTION__, [$filename, $permissions], false);
    }

    public function mknod(string $filename, int $flags, int $major = 0, int $minor = 0): void
    {
        $this->runMethod(__FUNCTION__, [$filename, $flags, $major, $minor], false);
    }

    public function setegid(int $groupId): void
    {
        $this->runMethod(__FUNCTION__, [$groupId], false);
    }

    public function seteuid(int $userId): void
    {
        $this->runMethod(__FUNCTION__, [$userId], false);
    }

    public function setgid(int $groupId): void
    {
        $this->runMethod(__FUNCTION__, [$groupId], false);
    }

    public function setpgid(int $processId, int $processGroupId): void
    {
        $this->runMethod(__FUNCTION__, [$processId, $processGroupId], false);
    }

    public function setrlimit(int $resource, int $softLimit, int $hardLimit): void
    {
        $this->runMethod(__FUNCTION__, [$resource, $softLimit, $hardLimit], false);
    }

    public function setsid(): int
    {
        return $this->runMethod(__FUNCTION__, [], -1);
    }

    public function setuid(int $userId): void
    {
        $this->runMethod(__FUNCTION__, [$userId], -1);
    }

    public function strerror(int $code): string
    {
        return (string)$this->runMethod(__FUNCTION__, [$code]);
    }

    public function sysconf(int $confId): int
    {
        return (int)$this->runMethod(__FUNCTION__, [$confId]);
    }

    public function times(): PosixTimesInfo
    {
        return new PosixTimesInfo($this->runMethod(__FUNCTION__, [], false));
    }

    public function ttyname(mixed $fileDescriptor): string
    {
        return $this->runMethod(__FUNCTION__, [$fileDescriptor], false);
    }

    public function uname(): PosixUNameInfo
    {
        return new PosixUNameInfo($this->runMethod(__FUNCTION__, [], false));
    }

    /**
     * Executes a POSIX method (considering the listener)
     *
     * @param string $method
     *        the method name matches with the corresponding function without the "posix_" prefix
     * @param array $args
     *        the arguments list (numeric)
     * @param mixed|null $errorResult
     *        a result value of the standard function that considering as error
     *        it leads to an exception
     *        NULL - there is no error value
     * @return mixed
     *         the result in the "standard" format (arrays instead objects, etc.)
     * @throws PosixException
     *         if the result was wrong or the listener threw this itself
     */
    protected function runMethod(string $method, array $args = [], mixed $errorResult = null): mixed
    {
        $code = null;
        $listener = $this->listener;
        $result = $listener?->before($method, $args, $code);
        if ($result === null) {
            $func = "posix_$method";
            $result = call_user_func_array($func, $args);
            if (($errorResult !== null) && ($errorResult === $result)) {
                $code = posix_get_last_error() ?: $code; // 0 means no error, so it doesn't change the code
            }
        }
        if ($listener) {
            $result = $listener->after($method, $args, $result, $code);
        }
        if (($errorResult !== null) && ($errorResult === $result)) {
            $this->error($code);
        }
        return $result;
    }

    protected function error(?int $code = null): never
    {
        throw new PosixException($code ?? posix_get_last_error());
    }

    protected function checkImplementation(string $method): void
    {
        $fn = "posix_$method";
        if (!function_exists($fn)) {
            throw new PosixNotImplementedException($method);
        }
    }

    protected function createGroupInfo(mixed $data): ?PosixGroupInfo
    {
        if (!is_array($data)) {
            return null;
        }
        return new PosixGroupInfo($data);
    }

    protected function createUserInfo(mixed $data): ?PosixUserInfo
    {
        if (!is_array($data)) {
            return null;
        }
        return new PosixUserInfo($data);
    }
}
