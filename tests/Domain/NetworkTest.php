<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Network;

final class NetworkTest extends TestCase
{
    public function test_constructor(): void
    {
        $network = Network::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/network.json'),
                true
            )
        );

        $this->assertEquals('d11c06f5-ff1c-41cd-8fc9-1ebcb3084501', (string) $network->getId());
        $this->assertEquals('An example (No SG)', $network->getDisplayName());
        $this->assertEquals('36616598-8e93-4118-a03c-94f99e5e1169', (string) $network->getDatacenterId());
        $this->assertEquals('man.zone03.ams02.cldin.net', $network->getManager());
        $this->assertEquals('185.109.217.64/26', $network->getCidrIpv4());
        $this->assertEquals('2a05:1500:600:4::/64', $network->getCidrIpv6());
        $this->assertEquals('2019-09-09T08:58:05+00:00', $network->getCreatedAt()->format(DateTime::W3C));
        $this->assertEquals('2019-09-09T08:58:05+00:00', $network->getUpdatedAt()->format(DateTime::W3C));
    }

    public function testConstructorThrowsExceptionForInvalidCreatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $network = Network::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/network_invalid_createdat.json'),
                true
            )
        );
    }

    public function testConstructorThrowsExceptionForInvalidUpdatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $network = Network::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/network_invalid_updatedat.json'),
                true
            )
        );
    }
}
