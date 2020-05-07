<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

final class Server
{
    /** @var ServerId */
    private $id;

    /** @var string */
    private $displayName;

    /** @var ServerStatus */
    private $status;

    /** @var bool */
    private $rescueIsoAttached;

    /** @var bool */
    private $hasSecurityGroup;

    /** @var DateTimeInterface */
    private $createdAt;

    /** @var DateTimeInterface */
    private $updatedAt;

    /** @var ?Offer */
    private $offer = null;

    /** @var ?DataCenter */
    private $dataCenter = null;

    private function __construct(
        ServerId $id,
        string $displayName,
        ServerStatus $status,
        bool $rescueIsoAttached,
        bool $hasSecurityGroup,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt,
        ?Offer $offer,
        ?DataCenter $dataCenter
    ) {
        $this->id = $id;
        $this->displayName = $displayName;
        $this->status = $status;
        $this->rescueIsoAttached = $rescueIsoAttached;
        $this->hasSecurityGroup = $hasSecurityGroup;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->offer = $offer;
        $this->dataCenter = $dataCenter;
    }

    /**
     * @param array<mixed> $data
     * @throws InvalidArgumentException
     * @throws InvalidUuidStringException
     */
    public static function fromArray(array $data): Server
    {
        $createdAt = DateTimeImmutable::createFromFormat(DateTime::W3C, $data['created_at']);
        if (!$createdAt instanceof DateTimeImmutable) {
            throw new InvalidArgumentException('Cannot instantiate createdAt');
        }

        $updatedAt = DateTimeImmutable::createFromFormat(DateTime::W3C, $data['updated_at']);
        if (!$updatedAt instanceof DateTimeImmutable) {
            throw new InvalidArgumentException('Cannot instantiate updatedAt');
        }

        $offer = isset($data['offer']['data']) ? Offer::fromArray($data['offer']['data']) : null;
        $dataCenter = isset($data['datacenter']['data']) ? DataCenter::fromArray($data['datacenter']['data']) : null;

        return new Server(
            ServerId::fromString($data['id']),
            $data['display_name'],
            ServerStatus::fromString($data['status']),
            boolval($data['rescue_iso_attached']),
            boolval($data['has_security_group']),
            $createdAt,
            $updatedAt,
            $offer,
            $dataCenter
        );
    }

    public function getId(): ServerId
    {
        return $this->id;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function getStatus(): ServerStatus
    {
        return $this->status;
    }

    public function isRescueIsoAttached(): bool
    {
        return $this->rescueIsoAttached;
    }

    public function isHasSecurityGroup(): bool
    {
        return $this->hasSecurityGroup;
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

    public function getDataCenter(): ?DataCenter
    {
        return $this->dataCenter;
    }
}
