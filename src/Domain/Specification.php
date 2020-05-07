<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Domain;

final class Specification
{

    private function __construct()
    {
    }

    /** @param array<mixed> $data */
    public static function fromArray(array $data): Specification
    {
        return new Specification();
    }
}
