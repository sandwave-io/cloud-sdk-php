<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

final class Network
{
    /** @var NetworkId */
    private $id;

    /** @var string */
    private $displayName;

    /** @var DatacenterId */
    private $datacenterId;

    /** @var string */
    private $manager;

    /** @var string */
    private $cidrIpv4;

    /** @var string */
    private $cidrIpv6;

    /** @var DateTimeInterface */
    private $createdAt;

    /** @var DateTimeInterface */
    private $updatedAt;

    private function __construct(
        NetworkId $id,
        string $displayName,
        DatacenterId $datacenterId,
        string $manager,
        string $cidrIpv4,
        string $cidrIpv6,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt
    ) {
        $this->id = $id;
        $this->displayName = $displayName;
        $this->datacenterId = $datacenterId;
        $this->manager = $manager;
        $this->cidrIpv4 = $cidrIpv4;
        $this->cidrIpv6 = $cidrIpv6;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data) : Network
    {
        $createdAt = DateTimeImmutable::createFromFormat(DateTime::W3C, $data['created_at']);
        if (! $createdAt instanceof DateTimeImmutable) {
            throw new InvalidArgumentException('Cannot instantiate createdAt');
        }

        $updatedAt = DateTimeImmutable::createFromFormat(DateTime::W3C, $data['updated_at']);
        if (! $updatedAt instanceof DateTimeImmutable) {
            throw new InvalidArgumentException('Cannot instantiate updatedAt');
        }

        return new Network(
            NetworkId::fromString($data['id']),
            $data['display_name'],
            DatacenterId::fromString($data['datacenter_id']),
            $data['manager'],
            $data['cidr_ipv4'],
            $data['cidr_ipv6'],
            $createdAt,
            $updatedAt
        );
    }

    public function getId() : NetworkId
    {
        return $this->id;
    }

    public function getDisplayName() : string
    {
        return $this->displayName;
    }

    public function getDatacenterId() : DatacenterId
    {
        return $this->datacenterId;
    }

    public function getManager() : string
    {
        return $this->manager;
    }

    public function getCidrIpv4() : string
    {
        return $this->cidrIpv4;
    }

    public function getCidrIpv6() : string
    {
        return $this->cidrIpv6;
    }

    public function getCreatedAt() : DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt() : DateTimeInterface
    {
        return $this->updatedAt;
    }
}
