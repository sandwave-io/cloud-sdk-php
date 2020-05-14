<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\DiskStatus;

final class DiskStatusTest extends TestCase
{
    public function testCanConstruct(): void
    {
        $creating = DiskStatus::creating();
        $this->assertEquals($creating, DiskStatus::fromString((string) $creating));

        $allocated = DiskStatus::allocated();
        $this->assertEquals($allocated, DiskStatus::fromString((string) $allocated));

        $ready = DiskStatus::ready();
        $this->assertEquals($ready, DiskStatus::fromString((string) $ready));

        $destroy = DiskStatus::destroy();
        $this->assertEquals($destroy, DiskStatus::fromString((string) $destroy));

        $expunging = DiskStatus::expunging();
        $this->assertEquals($expunging, DiskStatus::fromString((string) $expunging));

        $expunged = DiskStatus::expunged();
        $this->assertEquals($expunged, DiskStatus::fromString((string) $expunged));
    }

    public function testFromStringThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $bla = DiskStatus::fromString('nonexisting status!');
    }
}
