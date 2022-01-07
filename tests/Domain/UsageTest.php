<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Usage;

final class UsageTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function testCanConstruct(): void
    {
        $usage = Usage::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/usage.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );

        self::assertSame(16, $usage->getMemoryInGbs());
        self::assertSame(500, $usage->getStorageInGbs());
    }
}
