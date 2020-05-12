<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

final class Specification
{
    /** @var string */
    private $title;

    /** @var string */
    private $value;

    /** @var string */
    private $unit;

    public function __construct(string $title, string $value, string $unit)
    {
        $this->title = $title;
        $this->value = $value;
        $this->unit = $unit;
    }
}
