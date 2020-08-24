<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain\Compute;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Compute\Server;
use SandwaveIo\CloudSdkPhp\Domain\Compute\ServerStatus;

final class ServerTest extends TestCase
{
    public function testFromArray()
    {
        $server = Server::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/server.json'),
                true
            )
        );

        $this->assertSame(
            '6a6256cc-e6ff-41d2-9894-95a066d2b7a4',
            (string) $server->getId()
        );
        $this->assertSame('web01.seasidehosting.nl', $server->getDisplayName());
        $this->assertTrue($server->getStatus()->equals(ServerStatus::running()));
        $this->assertSame(false, $server->isRescueIsoAttached());
        $this->assertSame(true, $server->isHasSecurityGroup());
        $this->assertSame(
            '2020-03-04T13:48:35+00:00',
            $server->getCreatedAt()->format(DateTime::W3C)
        );
        $this->assertSame(
            '2020-03-30T11:01:17+00:00',
            $server->getUpdatedAt()->format(DateTime::W3C)
        );

        $this->assertSame(
            '2020-04-02T09:50:13+00:00',
            $server->getOffer()->getCreatedAt()->format(DateTime::W3C)
        );
        $this->assertSame('ams01', $server->getDataCenter()->getName());
    }

    public function testFromArrayNoOfferNoDatacenter()
    {
        $server = Server::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/server_no_offer_no_datacenter.json'),
                true
            )
        );

        $this->assertSame(
            '6a6256cc-e6ff-41d2-9894-95a066d2b7a4',
            (string) $server->getId()
        );
        $this->assertSame('web01.seasidehosting.nl', $server->getDisplayName());
        $this->assertTrue($server->getStatus()->equals(ServerStatus::running()));
        $this->assertSame(false, $server->isRescueIsoAttached());
        $this->assertSame(true, $server->isHasSecurityGroup());
        $this->assertSame(
            '2020-03-04T13:48:35+00:00',
            $server->getCreatedAt()->format(DateTime::W3C)
        );
        $this->assertSame(
            '2020-03-30T11:01:17+00:00',
            $server->getUpdatedAt()->format(DateTime::W3C)
        );

        $this->assertNull($server->getDataCenter());
        $this->assertNull($server->getOffer());
    }

    public function testConstructorThrowsExceptionOnInvalidCreatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $server = Server::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/server_invalid_createdat.json'),
                true
            )
        );
    }

    public function testConstructorThrowsExceptionOnInvalidUpdatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $server = Server::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/server_invalid_updatedat.json'),
                true
            )
        );
    }
}
