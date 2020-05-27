<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Usage;

final class UsageTest extends TestCase
{
    public function testCanConstruct(): void
    {
        $usage = Usage::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/usage.json'),
                true
            )
        );

        $this->assertSame(16, $usage->getMemoryInGbs());
        $this->assertSame(500, $usage->getStorageInGbs());
    }
}
