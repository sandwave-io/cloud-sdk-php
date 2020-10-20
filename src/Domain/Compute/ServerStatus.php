<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain\Compute;

use InvalidArgumentException;

final class ServerStatus
{
    private const STATUS_DELETING = 'deleting';
    private const STATUS_DELETED = 'deleted';
    private const STATUS_DEPLOYING = 'deploying';
    private const STATUS_DEPLOYED = 'deployed';
    private const STATUS_ERROR = 'error';
    private const STATUS_RUNNING = 'running';
    private const STATUS_STOPPING = 'stopping';
    private const STATUS_STOPPED = 'stopped';
    private const STATUS_STARTING = 'starting';
    private const STATUS_REBOOTING = 'rebooting';
    private const STATUS_DESTROYED = 'destroyed';

    /** @var string */
    private $value;

    /** @throws InvalidArgumentException */
    private function __construct(string $value)
    {
        $options = [
            ServerStatus::STATUS_DELETING,
            ServerStatus::STATUS_DELETED,
            ServerStatus::STATUS_ERROR,
            ServerStatus::STATUS_DEPLOYING,
            ServerStatus::STATUS_DEPLOYED,
            ServerStatus::STATUS_RUNNING,
            ServerStatus::STATUS_STOPPING,
            ServerStatus::STATUS_STOPPED,
            ServerStatus::STATUS_STARTING,
            ServerStatus::STATUS_REBOOTING,
            ServerStatus::STATUS_DESTROYED,
        ];

        if (! in_array(strtolower($value), $options)) {
            throw new InvalidArgumentException(
                sprintf('Server status must be one of \'%s\', got %s', implode(',', $options), $value)
            );
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function deploying(): ServerStatus
    {
        return new ServerStatus(ServerStatus::STATUS_DEPLOYING);
    }

    public static function deployed(): ServerStatus
    {
        return new ServerStatus(ServerStatus::STATUS_DEPLOYED);
    }

    public static function error(): ServerStatus
    {
        return new ServerStatus(ServerStatus::STATUS_ERROR);
    }

    public static function deleting(): ServerStatus
    {
        return new ServerStatus(ServerStatus::STATUS_DELETING);
    }

    public static function deleted(): ServerStatus
    {
        return new ServerStatus(ServerStatus::STATUS_DELETED);
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

    public static function rebooting(): ServerStatus
    {
        return new ServerStatus(ServerStatus::STATUS_REBOOTING);
    }

    public static function stopping(): ServerStatus
    {
        return new ServerStatus(ServerStatus::STATUS_STOPPING);
    }

    public static function destroyed(): ServerStatus
    {
        return new ServerStatus(ServerStatus::STATUS_DESTROYED);
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
}
