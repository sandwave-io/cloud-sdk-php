<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

use SandwaveIo\CloudSdkPhp\Domain\OfferId;
use SandwaveIo\CloudSdkPhp\Domain\Usage;

class LimitTest extends AbstractCloudSdkCase
{
    public function test_usage()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/usage.json',
            'get',
            'limits/current_usage'
        );

        $usage = $sdk->getUsage();

        $this->assertInstanceOf(Usage::class, $usage);
        $this->assertSame(58, $usage->getMemoryInGbs());
    }

    public function test_can_deploy_positive()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'get',
            'limits/can_deploy',
            'offer_id=175e7781-a186-47ed-91a7-b24e94b8e5c2&account_id=00000000-0000-0000-0000-000000000000'
        );

        $canDeploy = $sdk->canDeployOffer(OfferId::fromString('175e7781-a186-47ed-91a7-b24e94b8e5c2'));

        $this->assertTrue($canDeploy);
    }

    public function test_can_deploy_negative()
    {
        $sdk = $this->getSdkWithMockedClient(
            403,
            null,
            'get',
            'limits/can_deploy',
            'offer_id=175e7781-a186-47ed-91a7-b24e94b8e5c2&account_id=00000000-0000-0000-0000-000000000000'
        );

        $canDeploy = $sdk->canDeployOffer(OfferId::fromString('175e7781-a186-47ed-91a7-b24e94b8e5c2'));

        $this->assertFalse($canDeploy);
    }
}
