<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

final class Offer
{
    /** @var OfferId */
    private $id;

    /** @var AccountId|null */
    private $accountId;

    /** @var int */
    private $billingPeriodInMonths;

    /** @var Money */
    private $price;

    /** @var string */
    private $type;

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var bool */
    private $showInStore;

    /** @var SpecificationCollection */
    private $specifications;

    /** @var DateTimeInterface */
    private $createdAt;

    /** @var DateTimeInterface */
    private $updatedAt;

    /** @var string */
    private $sku;

    private function __construct(
        OfferId $id,
        ?AccountId $accountId,
        int $billingPeriodInMonths,
        Money $price,
        string $type,
        string $sku,
        string $name,
        string $description,
        bool $showInStore,
        SpecificationCollection $specifications,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt
    ) {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->billingPeriodInMonths = $billingPeriodInMonths;
        $this->price = $price;
        $this->type = $type;
        $this->sku = $sku;
        $this->name = $name;
        $this->description = $description;
        $this->showInStore = $showInStore;
        $this->specifications = $specifications;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param array<mixed> $data
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): Offer
    {
        try {
            $id = OfferId::fromString($data['id']);
        } catch (InvalidUuidStringException $e) {
            throw new InvalidArgumentException('Cannot instantiate id', 0, $e);
        }

        try {
            $accountId = is_null($data['account_id']) ? null : AccountId::fromString($data['account_id']);
        } catch (InvalidUuidStringException $e) {
            throw new InvalidArgumentException('Cannot instantiate accountId');
        }

        try {
            $price = Money::fromCents($data['price']);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Cannot instantiate price', 0, $e);
        }

        $createdAt = DateTimeImmutable::createFromFormat(DateTimeInterface::W3C, $data['created_at']);
        if (!$createdAt instanceof DateTimeImmutable) {
            throw new InvalidArgumentException('Cannot instantiate createdAt');
        }

        $updatedAt = DateTimeImmutable::createFromFormat(DateTimeInterface::W3C, $data['updated_at']);
        if (!$updatedAt instanceof DateTimeImmutable) {
            throw new InvalidArgumentException('Cannot instantiate updatedAt');
        }

        return new Offer(
            $id,
            $accountId,
            $data['billing_period'],
            $price,
            $data['type'],
            $data['sku'],
            $data['name'],
            $data['description'],
            boolval($data['show_in_store']),
            SpecificationCollection::fromArray($data['specifications']),
            $createdAt,
            $updatedAt
        );
    }

    public function isCustomOffering(): bool
    {
        return $this->accountId instanceof AccountId;
    }

    public function getId(): OfferId
    {
        return $this->id;
    }

    public function getAccountId(): ?AccountId
    {
        return $this->accountId;
    }

    public function getBillingPeriodInMonths(): int
    {
        return $this->billingPeriodInMonths;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isShowInStore(): bool
    {
        return $this->showInStore;
    }

    public function getSpecifications(): SpecificationCollection
    {
        return $this->specifications;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getSku(): string
    {
        return $this->sku;
    }
}
