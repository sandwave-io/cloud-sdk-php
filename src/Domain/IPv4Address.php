<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use InvalidArgumentException;

final class IPv4Address
{
    /** @var string */
    private $address;

    private function __construct(string $address)
    {
        $this->address = $address;
    }

    public function __toString(): string
    {
        return $this->address;
    }

    public static function fromString(string $address): IPv4Address
    {
        IPv4Address::assertValidIPv4Address($address);
        return new IPv4Address($address);
    }

    private static function assertValidIPv4Address(string $address): void
    {
        if (! filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            throw new InvalidArgumentException("{$address} is not a valid IPv4 address.");
        }
    }
}
