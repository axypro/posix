<?php

declare(strict_types=1);

namespace axy\posix\tests;

use axy\posix\{
    PosixConstants,
    RealPosix,
};
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

    public function testProcess(): void
    {
        $pid = posix_getpid();
        $ppid = posix_getppid();
        $grp = posix_getpgrp();
        $sid = posix_getsid($pid);
        $this->assertSame($pid, $this->posix->getpid());
        $this->assertSame($ppid, $this->posix->getppid());
        $this->assertSame($grp, $this->posix->getpgrp());
        if ($sid !== false) {
            $this->assertSame($sid, $this->posix->getsid($pid));
        }
    }

    /** @dataProvider providerGetUserInfo */
    public function testGetUserInfo(string $fn, mixed $arg, bool $exist = true): void
    {
        $data = call_user_func("posix_$fn", $arg);
        $info = $this->posix->$fn($arg);
        if (!$exist) {
            $this->assertFalse($data);
            $this->assertNull($info);
            return;
        } else {
            $this->assertNotEmpty($data);
        }
        $this->assertSame('root', $info->name);
        $this->assertSame(0, $info->uid);
        $this->assertSame(0, $info->gid);
        $this->assertSame($data['shell'] ?? '', $info->shell);
    }

    public static function providerGetUserInfo(): array
    {
        return [
            'getpwnam' => ['getpwnam', 'root'],
            'getpwuid' => ['getpwuid', 0],
            'wrong_getpwnam' => ['getpwnam', 'unknown-user', false],
            'wrong_getpwuid' => ['getpwuid', -1, false],
        ];
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

    public function testStrerror(): void
    {
        $this->assertSame(posix_strerror(0), $this->posix->strerror(0));
        $this->assertSame(posix_strerror(5), $this->posix->strerror(5));
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

    private RealPosix $posix;
}
