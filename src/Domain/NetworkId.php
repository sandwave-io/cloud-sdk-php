<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use Ramsey\Uuid\Exception\InvalidUuidStringException;

final class NetworkId extends AbstractId
{
    /** @throws InvalidUuidStringException */
    public static function fromString(string $value): NetworkId
    {
        return new NetworkId($value);
    }
}
