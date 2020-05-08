<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\DataCenter;

final class DataCenterTest extends TestCase
{
    public function test_constructor(): void
    {
        $datacenter = DataCenter::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/datacenter.json'),
                true
            )
        );

        $this->assertEquals('5427178b-fc9b-4561-8a8b-fb45353fdb37', (string) $datacenter->getId());
        $this->assertEquals('ams01', $datacenter->getName());
        $this->assertEquals('Tele 2', $datacenter->getDescription());
        $this->assertEquals('Amsterdam', $datacenter->getCity());
        $this->assertEquals('NL', $datacenter->getCountry());
        $this->assertEquals('Europe/Amsterdam', $datacenter->getTimezone());
        $this->assertFalse($datacenter->isStandardEnabled());
        $this->assertFalse($datacenter->isHaEnabled());
    }
}
