<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

final class Template
{
    /** @var TemplateId */
    private $id;

    /** @var string */
    private $displayName;

    /** @var string */
    private $operatingSystem;

    /** @var string */
    private $version;

    /** @var DateTimeInterface */
    private $createdAt;

    /** @var DateTimeInterface */
    private $updatedAt;

    private function __construct(
        TemplateId $id,
        string $displayName,
        string $operatingSystem,
        string $version,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt
    ) {
        $this->id = $id;
        $this->displayName = $displayName;
        $this->operatingSystem = $operatingSystem;
        $this->version = $version;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param array<mixed> $data
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): Template
    {
        try {
            $id = TemplateId::fromString($data['id']);
        } catch (InvalidUuidStringException $e) {
            throw new InvalidArgumentException('Could not instantiate id', 0, $e);
        }

        $createdAt = DateTimeImmutable::createFromFormat(DateTimeInterface::W3C, $data['created_at']);
        if (!$createdAt instanceof DateTimeImmutable) {
            throw new InvalidArgumentException('Could not instantiate createdAt');
        }

        $updatedAt = DateTimeImmutable::createFromFormat(DateTimeInterface::W3C, $data['updated_at']);
        if (!$updatedAt instanceof DateTimeImmutable) {
            throw new InvalidArgumentException('Could not instantiate updatedAt');
        }

        return new Template(
            $id,
            $data['display_name'],
            $data['os'],
            $data['version'],
            $createdAt,
            $updatedAt
        );
    }

    public function getId(): TemplateId
    {
        return $this->id;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function getOperatingSystem(): string
    {
        return $this->operatingSystem;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
