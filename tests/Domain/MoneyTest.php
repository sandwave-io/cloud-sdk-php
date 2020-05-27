<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Money;

final class MoneyTest extends TestCase
{
    public function testTostring(): void
    {
        $money = Money::fromCents(123456);

        $this->assertSame('1234.56', (string) $money);
    }
}
