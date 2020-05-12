<?php declare(strict_types=1);


namespace SandwaveIo\CloudSdkPhp\Tests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\CloudSdk;

class CloudSdkTest extends TestCase
{
    public function test_sdk_constructor()
    {
        $sdk = new CloudSdk('this-is-my-api-key', 'this-is-my-account-id');
        $this->assertNotNull($sdk);
    }
}
