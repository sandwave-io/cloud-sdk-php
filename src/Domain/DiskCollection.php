<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

final class DiskCollection extends AbstractCollection
{
    public function __construct(Disk ...$items)
    {
        parent::__construct(...$items);
    }

    public function current(): ?Disk
    {
        return $this->items->current();
    }

    public static function fromArray(array $data): DiskCollection
    {
        $disks = [];

        foreach ($data as $disk) {
            $disks[] = Disk::fromArray($disk);
        }

        return new DiskCollection(...$disks);
    }
}
