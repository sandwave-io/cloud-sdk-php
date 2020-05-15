<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use Ramsey\Uuid\Exception\InvalidUuidStringException;

abstract class AbstractId
{
    /** @var string */
    private $value;

    /** * @throws InvalidUuidStringException */
    protected function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString() : string
    {
        return $this->value;
    }

    /**
     * @throws InvalidUuidStringException
     *
     * @return mixed
     */
    abstract public static function fromString(string $value);
}
