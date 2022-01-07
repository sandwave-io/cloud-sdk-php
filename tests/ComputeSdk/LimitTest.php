<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\ComputeSdk;

use SandwaveIo\CloudSdkPhp\Domain\OfferId;

final class LimitTest extends AbstractComputeSdkCase
{
    public function test_usage(): void
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/usage.json',
            'get',
            'limits/current_usage'
        );

        $usage = $sdk->getUsage();

        self::assertSame(58, $usage->getMemoryInGbs());
    }

    public function test_can_deploy_positive(): void
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'get',
            'limits/can_deploy',
            'offer_id=175e7781-a186-47ed-91a7-b24e94b8e5c2&account_id=00000000-0000-0000-0000-000000000000'
        );

        $canDeploy = $sdk->canDeployOffer(OfferId::fromString('175e7781-a186-47ed-91a7-b24e94b8e5c2'));

        self::assertTrue($canDeploy);
    }

    public function test_can_deploy_negative(): void
    {
        $sdk = $this->getSdkWithMockedClient(
            403,
            null,
            'get',
            'limits/can_deploy',
            'offer_id=175e7781-a186-47ed-91a7-b24e94b8e5c2&account_id=00000000-0000-0000-0000-000000000000'
        );

        $canDeploy = $sdk->canDeployOffer(OfferId::fromString('175e7781-a186-47ed-91a7-b24e94b8e5c2'));

        self::assertFalse($canDeploy);
    }
}
