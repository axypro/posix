<?php

declare(strict_types=1);

namespace axy\posix;

/**
 * The list of constants which used in POSIX functions
 * Based on the list of global predefined constants (https://www.php.net/manual/en/posix.constants.php)
 * Have the same names without the "POSIX_" prefix
 * If a constant is introduced after the minimal supported PHP version the numeric value is used
 *
 * Some constants (POSIX_RLIMIT_KQUEUES, POSIX_RLIMIT_NPTS) are described in the PHP documentation as FreeBDS-depended
 * but are not defined in my IDE.
 * That values I found in the net are intercept with constants above.
 * For example: https://github.com/rust-lang/libc/blob/main/src/unix/bsd/freebsdlike/freebsd/mod.rs
 *
 * The constant "POSIX_PC_ALLOC_SYMLINK_MAX" is described in the documentation but is not defined
 */
final class PosixConstants
{
    public const F_OK = POSIX_F_OK;
    public const R_OK = POSIX_R_OK;
    public const W_OK = POSIX_W_OK;
    public const X_OK = POSIX_X_OK;
    public const S_IFBLK = POSIX_S_IFBLK;
    public const S_IFCHR = POSIX_S_IFCHR;
    public const S_IFIFO = POSIX_S_IFIFO;
    public const S_IFREG = POSIX_S_IFREG;
    public const S_IFSOCK = POSIX_S_IFSOCK;
    public const RLIMIT_AS = POSIX_RLIMIT_AS;
    public const RLIMIT_CORE = POSIX_RLIMIT_CORE;
    public const RLIMIT_CPU = POSIX_RLIMIT_CPU;
    public const RLIMIT_DATA = POSIX_RLIMIT_DATA;
    public const RLIMIT_FSIZE = POSIX_RLIMIT_FSIZE;
    public const RLIMIT_LOCKS = POSIX_RLIMIT_LOCKS;
    public const RLIMIT_MEMLOCK = POSIX_RLIMIT_MEMLOCK;
    public const RLIMIT_MSGQUEUE = POSIX_RLIMIT_MSGQUEUE;
    public const RLIMIT_NICE = POSIX_RLIMIT_NICE;
    public const RLIMIT_NOFILE = POSIX_RLIMIT_NOFILE;
    public const RLIMIT_NPROC = POSIX_RLIMIT_NPROC;
    public const RLIMIT_RSS = POSIX_RLIMIT_RSS;
    public const RLIMIT_RTPRIO = POSIX_RLIMIT_RTPRIO;
    public const RLIMIT_RTTIME = POSIX_RLIMIT_RTTIME;
    public const RLIMIT_SIGPENDING = POSIX_RLIMIT_SIGPENDING;
    public const RLIMIT_STACK = POSIX_RLIMIT_STACK;
    public const RLIMIT_INFINITY = POSIX_RLIMIT_INFINITY;
    public const PC_LINK_MAX = 0;
    public const PC_MAX_CANON = 1;
    public const PC_MAX_INPUT = 2;
    public const PC_NAME_MAX = 3;
    public const PC_PATH_MAX = 4;
    public const PC_PIPE_BUF = 5;
    public const PC_CHOWN_RESTRICTED = 6;
    public const PC_NO_TRUNC = 7;
    public const PC_ALLOC_SIZE_MIN = 18;
    public const SC_ARG_MAX = 0;
    public const SC_PAGESIZE = 30;
    public const SC_NPROCESSORS_CONF = 83;
    public const SC_NPROCESSORS_ONLN = 84;
}
