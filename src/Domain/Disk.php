<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

final class Disk
{
    /** @var DiskId */
    private $id;

    /** @var string */
    private $displayName;

    /** @var DiskStatus */
    private $status;

    /** @var DateTimeInterface */
    private $createdAt;

    /** @var DateTimeInterface */
    private $updatedAt;

    /** @var ?Offer */
    private $offer;

    private function __construct(
        DiskId $id,
        string $displayName,
        DiskStatus $status,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt,
        ?Offer $offer
    ) {
        $this->id = $id;
        $this->displayName = $displayName;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->offer = $offer;
    }

    /**
     * @param array<mixed> $data
     *
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): Disk
    {
        $createdAt = DateTimeImmutable::createFromFormat(DateTime::W3C, $data['created_at']);
        if (! $createdAt instanceof DateTimeImmutable) {
            throw new InvalidArgumentException('Cannot instantiate createdAt');
        }

        $updatedAt = DateTimeImmutable::createFromFormat(DateTime::W3C, $data['updated_at']);
        if (! $updatedAt instanceof DateTimeImmutable) {
            throw new InvalidArgumentException('Cannot instantiate updatedAt');
        }

        $offer = isset($data['offer']['data']) ? Offer::fromArray($data['offer']['data']) : null;

        return new Disk(
            DiskId::fromString($data['id']),
            $data['display_name'],
            DiskStatus::fromString($data['status']),
            $createdAt,
            $updatedAt,
            $offer
        );
    }

    public function getId(): DiskId
    {
        return $this->id;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function getStatus(): DiskStatus
    {
        return $this->status;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }
}
