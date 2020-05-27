<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use Ramsey\Uuid\Exception\InvalidUuidStringException;

final class OfferId extends AbstractId
{
    /** @throws InvalidUuidStringException */
    public static function fromString(string $value): OfferId
    {
        return new OfferId($value);
    }
}
