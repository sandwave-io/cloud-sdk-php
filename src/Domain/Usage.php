<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

final class Usage
{
    /** @var int */
    private $memoryInGbs;

    /** @var int */
    private $storageInGbs;

    private function __construct(int $memoryInGbs, int $storageInGbs)
    {
        $this->memoryInGbs = $memoryInGbs;
        $this->storageInGbs = $storageInGbs;
    }

    /** @param array<mixed> $data */
    public static function fromArray(array $data): Usage
    {
        return new Usage(
            $data['memory'],
            $data['storage']
        );
    }

    public function getMemoryInGbs(): int
    {
        return $this->memoryInGbs;
    }

    public function getStorageInGbs(): int
    {
        return $this->storageInGbs;
    }
}
