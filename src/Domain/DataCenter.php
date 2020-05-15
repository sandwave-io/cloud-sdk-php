<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain;

use InvalidArgumentException;

final class DataCenter
{
    /** @var DatacenterId */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var string */
    private $city;

    /** @var string */
    private $country;

    /** @var string */
    private $timezone;

    /** @var bool */
    private $standardEnabled;

    /** @var bool */
    private $haEnabled;

    private function __construct(
        DatacenterId $id,
        string $name,
        string $description,
        string $city,
        string $country,
        string $timezone,
        bool $standardEnabled,
        bool $haEnabled
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->city = $city;
        $this->country = $country;
        $this->timezone = $timezone;
        $this->standardEnabled = $standardEnabled;
        $this->haEnabled = $haEnabled;
    }

    /**
     * @param array<mixed> $data
     *
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data) : DataCenter
    {
        return new DataCenter(
            DatacenterId::fromString($data['id']),
            $data['name'],
            $data['description'],
            $data['city'],
            $data['country'],
            $data['timezone'],
            boolval($data['standard_enabled']),
            boolval($data['ha_enabled'])
        );
    }

    public function getId() : DatacenterId
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function getCity() : string
    {
        return $this->city;
    }

    public function getCountry() : string
    {
        return $this->country;
    }

    public function getTimezone() : string
    {
        return $this->timezone;
    }

    public function isStandardEnabled() : bool
    {
        return $this->standardEnabled;
    }

    public function isHaEnabled() : bool
    {
        return $this->haEnabled;
    }
}
