<?php

declare(strict_types=1);

namespace axy\posix;

class PosixResourceListLimits
{
    /**
     * The maximum size of the core file.
     * When 0, not core files are created.
     * When core files are larger than this size, they will be truncated at this size.
     */
    public readonly string|int $core;

    /** The maximum size of the memory of the process, in bytes */
    public readonly string|int $totalmem;

    /** The maximum size of the virtual memory for the process, in bytes */
    public readonly string|int $virtualmem;

    /** The maximum size of the data segment for the process, in bytes */
    public readonly string|int $data;

    /** The maximum size of the process stack, in bytes */
    public readonly string|int $stack;

    /** The maximum number of virtual pages resident in RAM */
    public readonly string|int $rss;

    /** The maximum number of processes that can be created for the real user ID of the calling process */
    public readonly string|int $maxproc;

    /** The maximum number of bytes of memory that may be locked into RAM */
    public readonly string|int $memlock;

    /** The amount of time the process is allowed to use the CPU */
    public readonly string|int $cpu;

    /** The maximum size of the data segment for the process, in bytes */
    public readonly string|int $filesize;

    /** One more than the maximum number of open file descriptors */
    public readonly string|int $openfiles;

    public function __construct(public readonly string $key, public readonly array $limits)
    {
        foreach (array_keys(get_class_vars(self::class)) as $k) {
            if (in_array($k, ['key', 'limits'])) {
                continue;
            }
            $v = $limits[$k] ?? null;
            if (!is_int($v)) {
                $v = (string)$v;
            }
            $this->$k = $v;
        }
    }
}
