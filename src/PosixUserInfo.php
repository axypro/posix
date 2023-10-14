<?php

declare(strict_types=1);

namespace axy\posix;

class PosixUserInfo
{
    /** The short (not real, full) username */
    public readonly string $name;

    /** The password in an encrypted format (just asterisk may be used) */
    public readonly string $password;

    /** The user ID */
    public readonly int $uid;

    /** The main group ID */
    public readonly int $gid;

    /** Obsoleted information */
    public readonly string $gecos;

    /** The home directory */
    public readonly string $dir;

    /** The path to the shell executable file */
    public readonly string $shell;

    public function __construct(public readonly array $data)
    {
        foreach (['name', 'password', 'gecos', 'dir', 'shell'] as $k) {
            $this->$k = (string)($data[$k] ?? '');
        }
        foreach (['uid', 'gid'] as $k) {
            $this->$k = (int)($data[$k] ?? -1);
        }
    }
}
