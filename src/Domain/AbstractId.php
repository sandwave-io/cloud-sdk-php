<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractId
{
    /** @var UuidInterface */
    private $value;

    /** * @throws InvalidUuidStringException */
    protected function __construct(string $value)
    {
        $this->value = Uuid::fromString($value);
    }

    public function __toString() : string
    {
        return $this->value->toString();
    }

    /**
     * @throws InvalidUuidStringException
     *
     * @return mixed
     */
    abstract public static function fromString(string $value);
}
