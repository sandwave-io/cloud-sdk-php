<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use DateTime;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Compute\Server;
use SandwaveIo\CloudSdkPhp\Domain\Compute\ServerCollection;
use SandwaveIo\CloudSdkPhp\Domain\Compute\ServerStatus;
use UnexpectedValueException;

final class ServerCollectionTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function testFromArrayOfArrays(): void
    {
        $serverData = json_decode(
            (string) file_get_contents('tests/Domain/json/server.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $servers = ServerCollection::fromArray([$serverData]);

        foreach ($servers as $server) {
            self::assertSame(
                '6a6256cc-e6ff-41d2-9894-95a066d2b7a4',
                (string) $server?->getId()
            );
            self::assertSame('web01.seasidehosting.nl', $server?->getDisplayName());
            self::assertTrue($server?->getStatus()->equals(ServerStatus::running()));
            self::assertFalse($server?->isRescueIsoAttached());
            self::assertTrue($server?->isHasSecurityGroup());
            self::assertSame(
                '2020-03-04T13:48:35+00:00',
                $server?->getCreatedAt()->format(DateTime::W3C)
            );
            self::assertSame(
                '2020-03-30T11:01:17+00:00',
                $server?->getUpdatedAt()->format(DateTime::W3C)
            );

            self::assertSame(
                '2020-04-02T09:50:13+00:00',
                $server?->getOffer()?->getCreatedAt()->format(DateTime::W3C)
            );
            self::assertSame('ams01', $server?->getDataCenter()?->getName());
        }
    }

    /**
     * @throws \JsonException
     */
    public function testFromArrayOfServers(): void
    {

        $serverData = Server::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/server.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );
        $servers = ServerCollection::fromArray([$serverData]);

        foreach ($servers as $server) {
            self::assertSame(
                '6a6256cc-e6ff-41d2-9894-95a066d2b7a4',
                (string) $server?->getId()
            );
            self::assertSame('web01.seasidehosting.nl', $server?->getDisplayName());
            self::assertTrue($server?->getStatus()->equals(ServerStatus::running()));
            self::assertFalse($server?->isRescueIsoAttached());
            self::assertTrue($server?->isHasSecurityGroup());
            self::assertSame(
                '2020-03-04T13:48:35+00:00',
                $server?->getCreatedAt()->format(DateTime::W3C)
            );
            self::assertSame(
                '2020-03-30T11:01:17+00:00',
                $server?->getUpdatedAt()->format(DateTime::W3C)
            );

            self::assertSame(
                '2020-04-02T09:50:13+00:00',
                $server?->getOffer()?->getCreatedAt()->format(DateTime::W3C)
            );
            self::assertSame('ams01', $server?->getDataCenter()?->getName());
        }
    }

    public function testFromArrayOfDifferentTypes(): void
    {
        $serversData = [
            'string',
            true,
            123,
        ];

        $this->expectException(UnexpectedValueException::class);
        ServerCollection::fromArray($serversData);
    }
}
