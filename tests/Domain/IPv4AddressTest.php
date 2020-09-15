<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\IPv4Address;

final class IPv4AddressTest extends TestCase
{
    public function ipAddresses(): array
    {
        return [
            ['123.123.123.123', true],
            ['123.123.123123', false],
            ['123.323.123.123', false],
            ['2a00:f10:101:1::1009', false],
        ];
    }

    /** @dataProvider ipAddresses */
    public function testFromAndToString(string $address, bool $valid): void
    {
        if (! $valid) {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage("{$address} is not a valid IPv4 address.");
        }
        $ipv4 = IPv4Address::fromString($address);
        $this->assertSame($address, (string) $ipv4);
    }
}
