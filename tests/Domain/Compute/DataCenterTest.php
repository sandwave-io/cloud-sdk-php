<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain\Compute;

use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Compute\DataCenter;

final class DataCenterTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function test_constructor(): void
    {
        $datacenter = DataCenter::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/datacenter.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );

        self::assertSame('5427178b-fc9b-4561-8a8b-fb45353fdb37', (string) $datacenter->getId());
        self::assertSame('ams01', $datacenter->getName());
        self::assertSame('Tele 2', $datacenter->getDescription());
        self::assertSame('Amsterdam', $datacenter->getCity());
        self::assertSame('NL', $datacenter->getCountry());
        self::assertSame('Europe/Amsterdam', $datacenter->getTimezone());
        self::assertFalse($datacenter->isStandardEnabled());
        self::assertFalse($datacenter->isHaEnabled());
    }
}
