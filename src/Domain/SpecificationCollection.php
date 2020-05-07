<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Domain;

final class SpecificationCollection extends AbstractCollection
{
    public function __construct(Specification ...$items)
    {
        parent::__construct(...$items);
    }

    public function current(): ?Specification
    {
        return $this->items->current();
    }

    /** @param array<array> $data */
    public static function fromArray(array $data): SpecificationCollection
    {
        $specifications = [];

        foreach ($data as $specification) {
            $specifications[] = Specification::fromArray($specification);
        }

        return new SpecificationCollection(...$specifications);
    }
}
