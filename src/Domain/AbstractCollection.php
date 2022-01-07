<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use ArrayIterator;
use Countable;
use Iterator;

/**
 * @implements Iterator <int | string, mixed>
 */
abstract class AbstractCollection implements Iterator, Countable
{
    /** @var ArrayIterator <mixed, mixed> */
    protected ArrayIterator $items;

    /** @param array<int, mixed> $items */
    protected function __construct(...$items)
    {
        $this->items = new ArrayIterator($items);
    }

    /** @return mixed */
    abstract public function current();

    final public function next(): void
    {
        $this->items->next();
    }

    /**
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    final public function key()
    {
        return $this->items->key();
    }

    final public function valid(): bool
    {
        return $this->items->valid();
    }

    final public function rewind(): void
    {
        $this->items->rewind();
    }

    final public function count(): int
    {
        return $this->items->count();
    }

    /**
     * @param array<array> $data
     *
     * @return mixed
     */
    abstract public static function fromArray(array $data);
}
