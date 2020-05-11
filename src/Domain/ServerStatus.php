<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use InvalidArgumentException;

final class ServerStatus
{
    private const STATUS_RUNNING = 'Running';
    private const STATUS_STOPPED = 'Stopped';

    /** @var string  */
    private $value;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(string $value)
    {
        $options = [
            ServerStatus::STATUS_RUNNING,
            ServerStatus::STATUS_STOPPED
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
