<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain\Compute;

use DateTime;
use DateTimeInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Compute\Disk;
use SandwaveIo\CloudSdkPhp\Domain\Compute\DiskStatus;

final class DiskTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function testFromArray(): void
    {
        $disk = Disk::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/disk.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );

        self::assertSame(
            '4031dfb0-a9a1-499b-8707-7f126bfcbda6',
            (string) $disk->getId()
        );
        self::assertSame('test', $disk->getDisplayName());
        self::assertTrue($disk->getStatus()->equals(DiskStatus::allocated()));
        self::assertSame(
            '2020-05-13T09:32:29+00:00',
            $disk->getCreatedAt()->format(DateTime::W3C)
        );
        self::assertSame(
            '2020-05-13T09:32:35+00:00',
            $disk->getUpdatedAt()->format(DateTime::W3C)
        );

        self::assertSame(
            '2019-09-09T08:58:09+00:00',
            $disk->getOffer()?->getCreatedAt()->format(DateTimeInterface::W3C)
        );
    }

    /**
     * @throws \JsonException
     */
    public function testFromArrayNoOffer(): void
    {
        $disk = Disk::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/disk_no_offer.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );

        self::assertSame(
            '4031dfb0-a9a1-499b-8707-7f126bfcbda6',
            (string) $disk->getId()
        );
        self::assertSame('test', $disk->getDisplayName());
        self::assertTrue($disk->getStatus()->equals(DiskStatus::allocated()));
        self::assertSame(
            '2020-05-13T09:32:29+00:00',
            $disk->getCreatedAt()->format(DateTime::W3C)
        );
        self::assertSame(
            '2020-05-13T09:32:35+00:00',
            $disk->getUpdatedAt()->format(DateTime::W3C)
        );

        self::assertNull($disk->getOffer());
    }

    /**
     * @throws \JsonException
     */
    public function testConstructorThrowsExceptionOnInvalidCreatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Disk::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/disk_invalid_createdat.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );
    }

    /**
     * @throws \JsonException
     */
    public function testConstructorThrowsExceptionOnInvalidUpdatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Disk::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/disk_invalid_updatedat.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );
    }
}
