<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

final class Money
{
    /** @var int */
    private $valueInCents;

    private function __construct(int $valueInCents)
    {
        $this->valueInCents = $valueInCents;
    }

    public function __toString() : string
    {
        return sprintf('%.2f', $this->valueInCents / 100);
    }

    public static function fromCents(int $cents) : self
    {
        return new self($cents);
    }

    public function toCents() : int
    {
        return $this->valueInCents;
    }
}
