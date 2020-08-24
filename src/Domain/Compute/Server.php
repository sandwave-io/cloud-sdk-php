<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain\Compute;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use SandwaveIo\CloudSdkPhp\Domain\Offer;

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

    /** @var string|null */
    private $ipv4Address;

    /** @var string|null */
    private $ipv6Address;

    /** @var NetworkId|null */
    private $networkId;

    /** @var ?DiskCollection */
    private $disks;

    /** @var ?Offer */
    private $offer;

    /** @var ?DataCenter */
    private $dataCenter;

    private function __construct(
        ServerId $id,
        string $displayName,
        ServerStatus $status,
        bool $rescueIsoAttached,
        bool $hasSecurityGroup,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt,
        ?string $ipv4Address,
        ?string $ipv6Address,
        ?NetworkId $networkId,
        ?Offer $offer,
        ?DataCenter $dataCenter,
        ?DiskCollection $disks
    ) {
        $this->id = $id;
        $this->displayName = $displayName;
        $this->status = $status;
        $this->rescueIsoAttached = $rescueIsoAttached;
        $this->hasSecurityGroup = $hasSecurityGroup;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->ipv4Address = $ipv4Address;
        $this->ipv6Address = $ipv6Address;
        $this->networkId = $networkId;
        $this->offer = $offer;
        $this->dataCenter = $dataCenter;
        $this->disks = $disks;
    }

    /**
     * @param array<mixed> $data
     *
     * @throws InvalidArgumentException
     * @throws InvalidUuidStringException
     */
    public static function fromArray(array $data): Server
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
        $dataCenter = isset($data['datacenter']['data']) ? DataCenter::fromArray($data['datacenter']['data']) : null;
        $disks = isset($data['disks']['data']) ? DiskCollection::fromArray($data['disks']['data']) : null;
        $ipv4Address = isset($data['ipv4_address']) ? $data['ipv4_address'] : null;
        $ipv6Address = isset($data['ipv6_address']) ? $data['ipv6_address'] : null;
        $networkId = isset($data['network_id']) ? NetworkId::fromString($data['network_id']) : null;

        return new Server(
            ServerId::fromString($data['id']),
            $data['display_name'],
            ServerStatus::fromString($data['status']),
            boolval($data['rescue_iso_attached']),
            boolval($data['has_security_group']),
            $createdAt,
            $updatedAt,
            $ipv4Address,
            $ipv6Address,
            $networkId,
            $offer,
            $dataCenter,
            $disks
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

    public function getIPv4Address(): ?string
    {
        return $this->ipv4Address;
    }

    public function getIPv6Address(): ?string
    {
        return $this->ipv6Address;
    }

    public function getNetworkId(): ?NetworkId
    {
        return $this->networkId;
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
