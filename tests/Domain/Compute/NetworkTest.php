<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain\Compute;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Compute\Network;

final class NetworkTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function test_constructor(): void
    {
        $network = Network::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/network.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );

        self::assertSame('d11c06f5-ff1c-41cd-8fc9-1ebcb3084501', (string) $network->getId());
        self::assertSame('An example (No SG)', $network->getDisplayName());
        self::assertSame('36616598-8e93-4118-a03c-94f99e5e1169', (string) $network->getDatacenterId());
        self::assertSame('man.zone03.ams02.cldin.net', $network->getManager());
        self::assertSame('185.109.217.64/26', $network->getCidrIpv4());
        self::assertSame('2a05:1500:600:4::/64', $network->getCidrIpv6());
        self::assertSame('2019-09-09T08:58:05+00:00', $network->getCreatedAt()->format(DateTime::W3C));
        self::assertSame('2019-09-09T08:58:05+00:00', $network->getUpdatedAt()->format(DateTime::W3C));
    }

    /**
     * @throws \JsonException
     */
    public function testConstructorThrowsExceptionForInvalidCreatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $network = Network::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/network_invalid_createdat.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );
    }

    /**
     * @throws \JsonException
     */
    public function testConstructorThrowsExceptionForInvalidUpdatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $network = Network::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/network_invalid_updatedat.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );
    }
}
