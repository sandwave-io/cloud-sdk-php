<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\DataCenter;

final class DataCenterTest extends TestCase
{
    public function test_constructor() : void
    {
        $datacenter = DataCenter::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/datacenter.json'),
                true
            )
        );

        $this->assertSame('5427178b-fc9b-4561-8a8b-fb45353fdb37', (string) $datacenter->getId());
        $this->assertSame('ams01', $datacenter->getName());
        $this->assertSame('Tele 2', $datacenter->getDescription());
        $this->assertSame('Amsterdam', $datacenter->getCity());
        $this->assertSame('NL', $datacenter->getCountry());
        $this->assertSame('Europe/Amsterdam', $datacenter->getTimezone());
        $this->assertFalse($datacenter->isStandardEnabled());
        $this->assertFalse($datacenter->isHaEnabled());
    }
}
