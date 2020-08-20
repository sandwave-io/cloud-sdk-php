<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

final class NetworkCollection extends AbstractCollection
{
    protected function __construct(Network ...$items)
    {
        parent::__construct(...$items);
    }

    public function current(): ?Network
    {
        return $this->items->current();
    }

    public static function fromArray(array $data): NetworkCollection
    {
        $networks = [];

        foreach ($data as $network) {
            $networks[] = Network::fromArray($network);
        }

        return new NetworkCollection(...$networks);
    }
}
