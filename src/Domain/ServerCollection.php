<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use UnexpectedValueException;

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

    /** @param array<array>|Server[] $data */
    public static function fromArray(array $data): ServerCollection
    {
        $servers = [];

        foreach ($data as $server) {
            if ($server instanceof Server) {
                $servers[] = $server;
            } elseif (is_array($server)) {
                $servers[] = Server::fromArray($server);
            } else {
                throw new UnexpectedValueException('ServerCollection::fromArray only takes a multi-dimensional array or an array of Servers.');
            }
        }

        return new ServerCollection(...$servers);
    }
}
