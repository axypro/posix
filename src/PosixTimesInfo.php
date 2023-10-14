<?php

declare(strict_types=1);

namespace axy\posix;

class PosixTimesInfo
{
    /** The number of clock ticks that have elapsed since reboot */
    public readonly int $ticks;

    /** user time used by the current process */
    public readonly int $utime;

    /** system time used by the current process */
    public readonly int $stime;

    /** user time used by current process and children */
    public readonly int $cutime;

    /** system time used by current process and children */
    public readonly int $cstime;

    public function __construct(public readonly array $data)
    {
        foreach (['ticks', 'utime', 'stime', 'cutime', 'cstime'] as $k) {
            $this->$k = (int)($data[$k] ?? 0);
        }
    }
}
