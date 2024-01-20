<?php

declare(strict_types=1);

namespace axy\posix\tests;

use axy\posix\{PosixConstants, PosixErrors, RealPosix};

/**
 * These tests run under the root (rUid and eUid).
 * See the "user" directive in the "docker/docker-compose.yml"
 */
class RootPosixTest extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->requiresDocker();
        if ((posix_getuid() !== 0) || (posix_geteuid() !== 0)) {
            $this->markTestSkipped('Test must be run under the root');
        }
        $this->posix = new RealPosix();
        if ($this->rGid === null) {
            $this->rGid = posix_getgid();
            $this->eGid = posix_getegid();
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();
        posix_seteuid(0);
        posix_setgid($this->rGid);
        posix_setegid($this->eGid);
    }

    public function testAccess(): void
    {
        if (!function_exists('posix_eaccess')) {
            $this->markTestSkipped('posix_eaccess() is not implemented in this PHP version');
        }
        $tmp = $this->tmpDir();
        $tmp->clear();
        $tmp->put('access.txt', '');
        $fn = $tmp->getPath('access.txt');
        chmod($fn, 0644);
        $this->assertTrue($this->posix->eaccess($fn));
        $this->assertTrue($this->posix->eaccess($fn, PosixConstants::R_OK));
        $this->assertTrue($this->posix->eaccess($fn, PosixConstants::W_OK));
        posix_seteuid(1234);
        $this->assertTrue($this->posix->eaccess($fn, PosixConstants::R_OK));
        $this->assertFalse($this->posix->eaccess($fn, PosixConstants::W_OK));
        $this->assertTrue($this->posix->access($fn, PosixConstants::R_OK));
        $this->assertTrue($this->posix->access($fn, PosixConstants::W_OK));
    }

    public function testUserIds(): void
    {
        $this->assertSame(0, $this->posix->getuid());
        $this->assertSame(0, $this->posix->geteuid());
        $this->posix->seteuid(1234);
        $this->assertSame(0, $this->posix->getuid());
        $this->assertSame(1234, $this->posix->geteuid());
        $this->assertSame(1234, posix_geteuid());
        $this->posix->seteuid(0);
        $this->assertSame(0, posix_geteuid());
        $this->posix->seteuid(1234);
        $this->posix->seteuid(1234);
        $this->expectPosixError(PosixErrors::EPERM);
        $this->posix->seteuid(1235);
    }

    public function testGroupIds(): void
    {
        $this->assertSame($this->rGid, $this->posix->getgid());
        $this->assertSame($this->eGid, $this->posix->getegid());
        $this->posix->setgid($this->rGid + 11);
        $this->assertSame($this->rGid + 11, $this->posix->getgid());
        $this->assertSame($this->rGid + 11, $this->posix->getegid());
        $this->posix->setegid($this->rGid + 22);
        $this->assertSame($this->rGid + 11, $this->posix->getgid());
        $this->assertSame($this->rGid + 22, $this->posix->getegid());
        $this->assertSame($this->rGid + 11, posix_getgid());
        $this->assertSame($this->rGid + 22, posix_getegid());
        $this->posix->setgid(0);
        $this->assertSame(0, $this->posix->getgid());
        $this->assertSame(0, $this->posix->getegid());
    }

    public function testInitGroups(): void
    {
        $this->posix->initgroups('axy_tester', 9928);
        $gids = $this->posix->getgroups();
        sort($gids);
        $expectedGids = [
            posix_getgrnam('axy_worker')['gid'] ?? null,
            1234,
            9928,
        ];
        sort($expectedGids);
        $this->assertEquals([1000, 1234, 9928], $gids);
        $this->posix->initgroups('root', 0);
        $this->assertEquals([0], $this->posix->getgroups());
    }

    private RealPosix $posix;
    private ?int $rGid = null;
    private int $eGid = 0;
}
