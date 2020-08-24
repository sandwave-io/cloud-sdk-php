<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain\Compute;

use Ramsey\Uuid\Exception\InvalidUuidStringException;
use SandwaveIo\CloudSdkPhp\Domain\AbstractId;

final class NetworkId extends AbstractId
{
    /** @throws InvalidUuidStringException */
    public static function fromString(string $value): NetworkId
    {
        return new NetworkId($value);
    }
}
