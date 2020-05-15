<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use Ramsey\Uuid\Exception\InvalidUuidStringException;

final class ServerId extends AbstractId
{
    /** @throws InvalidUuidStringException */
    public static function fromString(string $value) : ServerId
    {
        return new ServerId($value);
    }
}
