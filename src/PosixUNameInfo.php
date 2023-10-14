<?php

declare(strict_types=1);

namespace axy\posix;

class PosixUNameInfo
{
    /** Operating system name */
    public readonly string $sysname;

    /** System name */
    public readonly string $nodename;

    /** Operating system release */
    public readonly string $release;

    /** Operating system version */
    public readonly string $version;

    /** System architecture */
    public readonly string $machine;

    /** DNS domainname */
    public readonly string $domainname;

    public function __construct(public readonly array $data)
    {
        foreach (['sysname', 'nodename', 'release', 'version', 'machine', 'domainname'] as $k) {
            $this->$k = (string)($data[$k] ?? '');
        }
    }
}
