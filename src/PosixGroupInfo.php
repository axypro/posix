<?php

declare(strict_types=1);

namespace axy\posix;

class PosixGroupInfo
{
    /** The short (not real, full) name of the group */
    public readonly string $name;

    /** The group password in an encrypted format (just asterisk may be used) */
    public readonly string $passwd;

    /** The group ID */
    public readonly int $gid;

    /** The list of member logins */
    public readonly array $members;

    public function __construct(public readonly array $data)
    {
        $this->name = (string)($data['name'] ?? '');
        $this->passwd = (string)($data['passwd'] ?? '');
        $this->gid = (int)($data['gid'] ?? 0);
        $this->members = $data['members'] ?? [];
    }
}
