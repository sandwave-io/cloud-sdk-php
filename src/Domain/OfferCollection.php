<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

final class OfferCollection extends AbstractCollection
{
    protected function __construct(Offer ...$items)
    {
        parent::__construct(...$items);
    }

    public function current(): ?Offer
    {
        return $this->items->current();
    }

    /** @param array<array> $data */
    public static function fromArray(array $data): OfferCollection
    {
        $offers = [];

        foreach ($data as $offer) {
            $offers[] = Offer::fromArray($offer);
        }

        return new OfferCollection(...$offers);
    }
}
