<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use InvalidArgumentException;

final class ServerStatus
{
    private const STATUS_RUNNING = 'Running';
    private const STATUS_STOPPED = 'Stopped';
    private const STATUS_STARTING = 'starting';

    /** @var string  */
    private $value;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(string $value)
    {
        $options = [
            ServerStatus::STATUS_RUNNING,
            ServerStatus::STATUS_STOPPED,
            ServerStatus::STATUS_STARTING
        ];

        if (!in_array($value, $options)) {
            throw new InvalidArgumentException(
                sprintf('Server status must be one of \'%s\', got %s', implode(',', $options), $value)
            );
        }

        $this->value = $value;
    }

    public static function running(): ServerStatus
    {
        return new ServerStatus(ServerStatus::STATUS_RUNNING);
    }

    public static function stopped(): ServerStatus
    {
        return new ServerStatus(ServerStatus::STATUS_STOPPED);
    }

    public static function starting(): ServerStatus
    {
        return new ServerStatus(ServerStatus::STATUS_STARTING);
    }

    public function equals(ServerStatus $other): bool
    {
        return $this->value === $other->value;
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
