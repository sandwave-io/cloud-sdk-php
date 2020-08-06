<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

final class DataCenterCollection extends AbstractCollection
{
    public function __construct(DataCenter ...$items)
    {
        parent::__construct(...$items);
    }

    public function current(): ?DataCenter
    {
        return $this->items->current();
    }

    /** @param array<array> $data */
    public static function fromArray(array $data): DataCenterCollection
    {
        $datacenters = [];

        foreach ($data as $datacenter) {
            $datacenters[] = DataCenter::fromArray($datacenter);
        }

        return new DataCenterCollection(...$datacenters);
    }
}
