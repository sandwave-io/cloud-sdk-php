<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use SandwaveIo\CloudSdkPhp\CloudSdk;
use SandwaveIo\CloudSdkPhp\Domain\AccountId;

class CloudSdkTest extends TestCase
{
    public function test_sdk_constructor()
    {
        $sdk = new CloudSdk('this-is-my-api-key', AccountId::fromString(Uuid::NIL));
        $this->assertNotNull($sdk);
    }
}
