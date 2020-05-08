<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use Iterator;
use Countable;
use ArrayIterator;

abstract class AbstractCollection implements Iterator, Countable
{
    /** @var ArrayIterator  */
    protected $items;

    /** @param array<int, mixed> $items */
    public function __construct(...$items)
    {
        $this->items = new ArrayIterator($items);
    }

    /** @return mixed */
    abstract public function current();

    public function next(): void
    {
        $this->items->next();
    }

    /**
     * @codeCoverageIgnore
     * @return mixed
     */
    public function key()
    {
        return $this->items->key();
    }

    public function valid(): bool
    {
        return $this->items->valid();
    }

    public function rewind(): void
    {
        $this->items->rewind();
    }

    public function count(): int
    {
        return $this->items->count();
    }

    /**
     * @param array<array> $data
     * @return mixed
     */
    abstract public static function fromArray(array $data);
}
