<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain\Compute;

use DateTime;
use DateTimeInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Compute\Server;
use SandwaveIo\CloudSdkPhp\Domain\Compute\ServerStatus;

final class ServerTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function testFromArray(): void
    {
        $server = Server::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/server.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );

        self::assertSame(
            '6a6256cc-e6ff-41d2-9894-95a066d2b7a4',
            (string) $server->getId()
        );
        self::assertSame('web01.seasidehosting.nl', $server->getDisplayName());
        self::assertTrue($server->getStatus()->equals(ServerStatus::running()));
        self::assertFalse($server->isRescueIsoAttached());
        self::assertTrue($server->isHasSecurityGroup());
        self::assertSame(
            '2020-03-04T13:48:35+00:00',
            $server->getCreatedAt()->format(DateTimeInterface::W3C)
        );
        self::assertSame(
            '2020-03-30T11:01:17+00:00',
            $server->getUpdatedAt()->format(DateTimeInterface::W3C)
        );

        self::assertSame(
            '2020-04-02T09:50:13+00:00',
            $server->getOffer()?->getCreatedAt()->format(DateTime::W3C)
        );
        self::assertSame('ams01', $server->getDataCenter()?->getName());
    }

    /**
     * @throws \JsonException
     */
    public function testFromArrayNoOfferNoDatacenter(): void
    {
        $server = Server::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/server_no_offer_no_datacenter.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );

        self::assertSame(
            '6a6256cc-e6ff-41d2-9894-95a066d2b7a4',
            (string) $server->getId()
        );
        self::assertSame('web01.seasidehosting.nl', $server->getDisplayName());
        self::assertTrue($server->getStatus()->equals(ServerStatus::running()));
        self::assertFalse($server->isRescueIsoAttached());
        self::assertTrue($server->isHasSecurityGroup());
        self::assertSame(
            '2020-03-04T13:48:35+00:00',
            $server->getCreatedAt()->format(DateTimeInterface::W3C)
        );
        self::assertSame(
            '2020-03-30T11:01:17+00:00',
            $server->getUpdatedAt()->format(DateTimeInterface::W3C)
        );

        self::assertNull($server->getDataCenter());
        self::assertNull($server->getOffer());
    }

    /**
     * @throws \JsonException
     */
    public function testConstructorThrowsExceptionOnInvalidCreatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $server = Server::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/server_invalid_createdat.json'),
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
        $server = Server::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/server_invalid_updatedat.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );
    }
}
