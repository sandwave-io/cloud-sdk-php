<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain\Compute;

use SandwaveIo\CloudSdkPhp\Domain\AbstractCollection;

final class DataCenterCollection extends AbstractCollection
{
    protected function __construct(DataCenter ...$items)
    {
        parent::__construct(...$items);
    }

    public function current(): ?DataCenter
    {
        return $this->items->current();
    }

    public static function fromArray(array $data): DataCenterCollection
    {
        $datacenters = [];

        foreach ($data as $datacenter) {
            $datacenters[] = DataCenter::fromArray($datacenter);
        }

        return new DataCenterCollection(...$datacenters);
    }
}
