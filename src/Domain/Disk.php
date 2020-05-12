<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

final class Disk
{
    private function __construct()
    {
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data) : Disk
    {
        return new Disk();
    }
}
