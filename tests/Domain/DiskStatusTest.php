<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\DiskStatus;

final class DiskStatusTest extends TestCase
{
    public function testCanConstruct() : void
    {
        $creating = DiskStatus::creating();
        $this->assertTrue(DiskStatus::fromString((string) $creating)->equals($creating));

        $allocated = DiskStatus::allocated();
        $this->assertTrue(DiskStatus::fromString((string) $allocated)->equals($allocated));

        $ready = DiskStatus::ready();
        $this->assertTrue(DiskStatus::fromString((string) $ready)->equals($ready));

        $destroy = DiskStatus::destroy();
        $this->assertTrue(DiskStatus::fromString((string) $destroy)->equals($destroy));

        $expunging = DiskStatus::expunging();
        $this->assertTrue(DiskStatus::fromString((string) $expunging)->equals($expunging));

        $expunged = DiskStatus::expunged();
        $this->assertTrue(DiskStatus::fromString((string) $expunged)->equals($expunged));
    }

    public function testFromStringThrowsException() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $bla = DiskStatus::fromString('nonexisting status!');
    }
}
