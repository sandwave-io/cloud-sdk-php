<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain\Compute;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Compute\DiskStatus;

final class DiskStatusTest extends TestCase
{
    public function testCanConstruct(): void
    {
        $creating = DiskStatus::creating();
        self::assertTrue(DiskStatus::fromString((string) $creating)->equals($creating));

        $allocated = DiskStatus::allocated();
        self::assertTrue(DiskStatus::fromString((string) $allocated)->equals($allocated));

        $ready = DiskStatus::ready();
        self::assertTrue(DiskStatus::fromString((string) $ready)->equals($ready));

        $destroy = DiskStatus::destroy();
        self::assertTrue(DiskStatus::fromString((string) $destroy)->equals($destroy));

        $expunging = DiskStatus::expunging();
        self::assertTrue(DiskStatus::fromString((string) $expunging)->equals($expunging));

        $expunged = DiskStatus::expunged();
        self::assertTrue(DiskStatus::fromString((string) $expunged)->equals($expunged));
    }

    public function testFromStringThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $bla = DiskStatus::fromString('nonexisting status!');
    }
}
