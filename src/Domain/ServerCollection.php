<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

final class ServerCollection extends AbstractCollection
{
    protected function __construct(Server ...$items)
    {
        parent::__construct(...$items);
    }

    public function current(): ?Server
    {
        return $this->items->current();
    }

    /** @param array<array> $data */
    public static function fromArray(array $data): ServerCollection
    {
        $servers = [];

        foreach ($data as $server) {
            $servers[] = Server::fromArray($server);
        }

        return new ServerCollection(...$servers);
    }
}
