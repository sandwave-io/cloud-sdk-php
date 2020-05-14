<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use InvalidArgumentException;

final class DiskStatus
{
    private const STATUS_CREATING = 'Creating';
    private const STATUS_ALLOCATED = 'Allocated';
    private const STATUS_READY = 'Ready';
    private const STATUS_DESTROY = 'Destroy';
    private const STATUS_EXPUNGING = 'Expunging';
    private const STATUS_EXPUNGED = 'Expunged';

    /** @var string */
    private $value;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(string $value)
    {
        $options = [
            DiskStatus::STATUS_CREATING,
            DiskStatus::STATUS_ALLOCATED,
            DiskStatus::STATUS_READY,
            DiskStatus::STATUS_DESTROY,
            DiskStatus::STATUS_EXPUNGING,
            DiskStatus::STATUS_EXPUNGED,
        ];

        if (! in_array($value, $options)) {
            throw new InvalidArgumentException(
                sprintf('Disk status must be one of \'%s\', got %s', implode(',', $options), $value)
            );
        }

        $this->value = $value;
    }

    public function __toString() : string
    {
        return $this->value;
    }

    public static function creating() : DiskStatus
    {
        return new DiskStatus(DiskStatus::STATUS_CREATING);
    }

    public static function allocated() : DiskStatus
    {
        return new DiskStatus(DiskStatus::STATUS_ALLOCATED);
    }

    public static function ready() : DiskStatus
    {
        return new DiskStatus(DiskStatus::STATUS_READY);
    }

    public static function destroy() : DiskStatus
    {
        return new DiskStatus(DiskStatus::STATUS_DESTROY);
    }

    public static function expunging() : DiskStatus
    {
        return new DiskStatus(DiskStatus::STATUS_EXPUNGING);
    }

    public static function expunged() : DiskStatus
    {
        return new DiskStatus(DiskStatus::STATUS_EXPUNGED);
    }

    public function equals(DiskStatus $other) : bool
    {
        return $this->value === $other->value;
    }

    /** @throws InvalidArgumentException */
    public static function fromString(string $value) : DiskStatus
    {
        return new DiskStatus($value);
    }
}
