<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use InvalidArgumentException;

final class ServerStatus
{
    private const STATUS_RUNNING = 'Running';

    /** @var string  */
    private $value;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(string $value)
    {
        $options = [
            ServerStatus::STATUS_RUNNING
        ];

        if (!in_array($value, $options)) {
            throw new InvalidArgumentException(
                sprintf('Server status must be one of %s', implode(',', $options))
            );
        }

        $this->value = $value;
    }

    /** @throws InvalidArgumentException */
    public static function fromString(string $value): ServerStatus
    {
        return new ServerStatus($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
