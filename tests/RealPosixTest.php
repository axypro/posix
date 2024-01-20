<?php

declare(strict_types=1);

namespace axy\posix\tests;

use axy\posix\{exceptions\PosixNotImplementedException, PosixConstants, PosixErrors, RealPosix};
use axy\posix\exceptions\PosixException;

class RealPosixTest extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->posix = new RealPosix();
    }

    public function testAccess(): void
    {
        $tmp = $this->tmpDir();
        $tmp->clear();
        $tmp->put('one.txt', '');
        $fn = $tmp->getPath('one.txt');
        chmod($fn, 0644);
        $this->assertTrue($this->posix->access($fn, PosixConstants::R_OK));
        $this->assertFalse($this->posix->access($fn, PosixConstants::X_OK));
        $none = $tmp->getPath('two.txt');
        $this->assertFalse($this->posix->access($none, PosixConstants::R_OK));
    }

    public function testEAccess(): void
    {
        if (!function_exists('posix_eaccess')) {
            $this->expectException(PosixNotImplementedException::class);
            $this->posix->eaccess(__FILE__);
            return;
        }
        $tmp = $this->tmpDir();
        $tmp->clear();
        $tmp->put('one.txt', '');
        $fn = $tmp->getPath('one.txt');
        chmod($fn, 0644);
        $this->assertTrue($this->posix->eaccess($fn, PosixConstants::R_OK));
        $this->assertFalse($this->posix->eaccess($fn, PosixConstants::X_OK));
        $none = $tmp->getPath('two.txt');
        $this->assertFalse($this->posix->eaccess($none, PosixConstants::R_OK));
    }

    public function testCtermid(): void
    {
        $expected = posix_ctermid();
        if ($expected === false) {
            $this->expectException(PosixException::class);
        }
        $this->assertSame($expected, $this->posix->ctermid());
    }

    public function testGetcwd(): void
    {
        $this->assertSame(posix_getcwd(), $this->posix->getcwd());
    }

    public function testGetIds(): void
    {
        $this->assertSame(posix_getegid(), $this->posix->getegid());
        $this->assertSame(posix_geteuid(), $this->posix->geteuid());
        $this->assertSame(posix_getgid(), $this->posix->getgid());
        $this->assertSame(posix_getuid(), $this->posix->getuid());
    }

    public function testProcessIds(): void
    {
        $this->assertSame(posix_getpid(), $this->posix->getpid());
        $this->assertSame(posix_getppid(), $this->posix->getppid());
        $this->assertSame(posix_getpgrp(), $this->posix->getpgrp());
    }

    public function testGetSid(): void
    {
        $pid = posix_getpid();
        $sid = posix_getsid($pid);
        if ($sid === false) {
            $this->expectPosixError();
        }
        $this->assertSame($sid, $this->posix->getsid($pid));
    }

    public function testGetPGid(): void
    {
        $pid = posix_getpid();
        $pgid = posix_getpgid($pid);
        if ($pgid === false) {
            $this->expectPosixError();
        }
        $this->assertSame($pgid, $this->posix->getpgid($pid));
    }

    public function testGetpwuid(): void
    {
        $this->requiresDocker();
        $info = $this->posix->getpwuid(1735);
        $this->assertSame(1735, $info?->uid);
        $this->assertSame(1735, $info->gid);
        $this->assertSame('axy_tester', $info->name);
        $this->assertSame('fake_tester', $info->gecos);
        $this->assertSame('/home/tester', $info->dir);
        $data = posix_getpwuid(1735) ?: null;
        $this->assertSame($data['shell'] ?? null, $info->shell);
        $this->assertNull($this->posix->getpwuid(1801));
    }

    public function testGetpwnam(): void
    {
        $this->requiresDocker();
        $this->assertSame('fake_tester', $this->posix->getpwnam('axy_tester')?->gecos);
        $this->assertNull($this->posix->getpwnam('xxx_xxx'));
    }

    public function testGetgrgid(): void
    {
        $this->requiresDocker();
        $info = $this->posix->getgrgid(1234);
        $this->assertSame(1234, $info?->gid);
        $this->assertSame('axy_test', $info->name);
        $this->assertSame([
            'axy_tester',
        ], $info->members);
        $this->assertNull($this->posix->getgrgid(2345));
    }

    public function testGetgrnam(): void
    {
        $this->requiresDocker();
        $this->assertSame(1234, $this->posix->getgrnam('axy_test')?->gid);
        $this->assertSame(1735, $this->posix->getgrnam('axy_tester')?->gid);
        $this->assertNull($this->posix->getgrnam('xxx_xxx'));
    }

    public function testGetrlimit(): void
    {
        $data = posix_getrlimit();
        if ($data === false) {
            $this->expectException(PosixException::class);
        }
        $info = $this->posix->getrlimit();
        $this->assertSame($data['hard cpu'] ?? '', $info->hard->cpu);
    }

    /** @dataProvider providerStrerror */
    public function testStrerror(int $code): void
    {
        $this->assertSame(posix_strerror($code), $this->posix->strerror($code));
    }

    public static function providerStrerror(): array
    {
        return [
            [PosixErrors::EIO],
            [0],
            [-1],
        ];
    }

    public function testTimes(): void
    {
        $data = posix_times();
        if ($data === false) {
            $this->expectException(PosixException::class);
        }
        $times = $this->posix->times();
        $this->assertGreaterThanOrEqual($data['ticks'] ?? null, $times->ticks);
        $this->assertSame($times->data['ticks'] ?? null, $times->ticks);
    }

    public function testUname(): void
    {
        $data = posix_uname();
        if ($data === false) {
            $this->expectException(PosixException::class);
        }
        $uname = $this->posix->uname();
        $this->assertSame($data['nodename'] ?? '', $uname->nodename);
    }

    public function testGetlogin(): void
    {
        $login = posix_getlogin();
        if ($login === false) {
            $this->expectException(PosixException::class);
        }
        $this->assertSame($login, $this->posix->getlogin());
    }

    public function testGetgroups(): void
    {
        $expected = posix_getgroups();
        if ($expected === false) {
            $this->expectException(PosixException::class);
        }
        $actual = $this->posix->getgroups();
        if (is_array($expected)) {
            sort($expected);
            sort($actual);
            $this->assertSame($expected, $actual);
        }
    }

    public function testIsAtty(): void
    {
        $fp = fopen(__FILE__, "rt");
        $this->assertFalse($this->posix->isatty($fp));
        fclose($fp);
        $this->assertSame(posix_isatty(STDOUT), $this->posix->isatty(STDOUT));
    }

    public function testTtyname(): void
    {
        $name = posix_ttyname(STDOUT);
        if ($name === false) {
            $this->expectPosixError();
        }
        $this->assertSame($name, $this->posix->ttyname(STDOUT));
    }

    public function testFpathconf(): void
    {
        if (!function_exists('posix_fpathconf')) {
            $this->expectException(PosixNotImplementedException::class);
            $this->posix->fpathconf(1, PosixConstants::PC_PATH_MAX);
            return;
        }
        $fp = fopen(__DIR__, "r");
        try {
            $expected = posix_fpathconf($fp, PosixConstants::PC_PATH_MAX);
            if ($expected === false) {
                $this->expectPosixError();
            }
            $this->assertSame($expected, $this->posix->fpathconf($fp, PosixConstants::PC_PATH_MAX));
        } finally {
            fclose($fp);
        }
    }

    public function testSysconf(): void
    {
        if (!function_exists('posix_sysconf')) {
            $this->expectException(PosixNotImplementedException::class);
            $this->posix->sysconf(PosixConstants::SC_PAGESIZE);
            return;
        }
        $expected = posix_sysconf(PosixConstants::SC_PAGESIZE);
        $this->assertSame($expected, $this->posix->sysconf(PosixConstants::SC_PAGESIZE));
    }

    private RealPosix $posix;
}
