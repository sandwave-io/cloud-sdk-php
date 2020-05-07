<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use Ramsey\Uuid\Exception\InvalidUuidStringException;

final class TemplateId extends BaseId
{
    /** @throws InvalidUuidStringException */
    public static function fromString(string $value): TemplateId
    {
        return new TemplateId($value);
    }
}
