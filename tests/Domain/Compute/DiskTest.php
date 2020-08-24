<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain\Compute;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Compute\Disk;
use SandwaveIo\CloudSdkPhp\Domain\Compute\DiskStatus;

final class DiskTest extends TestCase
{
    public function testFromArray()
    {
        $disk = Disk::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/disk.json'),
                true
            )
        );

        $this->assertSame(
            '4031dfb0-a9a1-499b-8707-7f126bfcbda6',
            (string) $disk->getId()
        );
        $this->assertSame('test', $disk->getDisplayName());
        $this->assertTrue($disk->getStatus()->equals(DiskStatus::allocated()));
        $this->assertSame(
            '2020-05-13T09:32:29+00:00',
            $disk->getCreatedAt()->format(DateTime::W3C)
        );
        $this->assertSame(
            '2020-05-13T09:32:35+00:00',
            $disk->getUpdatedAt()->format(DateTime::W3C)
        );

        $this->assertSame(
            '2019-09-09T08:58:09+00:00',
            $disk->getOffer()->getCreatedAt()->format(DateTime::W3C)
        );
    }

    public function testFromArrayNoOffer()
    {
        $disk = Disk::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/disk_no_offer.json'),
                true
            )
        );

        $this->assertSame(
            '4031dfb0-a9a1-499b-8707-7f126bfcbda6',
            (string) $disk->getId()
        );
        $this->assertSame('test', $disk->getDisplayName());
        $this->assertTrue($disk->getStatus()->equals(DiskStatus::allocated()));
        $this->assertSame(
            '2020-05-13T09:32:29+00:00',
            $disk->getCreatedAt()->format(DateTime::W3C)
        );
        $this->assertSame(
            '2020-05-13T09:32:35+00:00',
            $disk->getUpdatedAt()->format(DateTime::W3C)
        );

        $this->assertNull($disk->getOffer());
    }

    public function testConstructorThrowsExceptionOnInvalidCreatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Disk::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/disk_invalid_createdat.json'),
                true
            )
        );
    }

    public function testConstructorThrowsExceptionOnInvalidUpdatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Disk::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/disk_invalid_updatedat.json'),
                true
            )
        );
    }
}
