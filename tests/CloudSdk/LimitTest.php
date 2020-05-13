<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

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

        $json = $sdk->getUsage();

        $this->assertTrue(is_array($json));
        $this->assertNotSame([], $json);
        $this->assertArrayContains('memory', 58, $json);
    }

    public function test_can_deploy_positive()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'get',
            'limits/can_deploy'
        );

        $canDeploy = $sdk->canDeployOffer('175e7781-a186-47ed-91a7-b24e94b8e5c2');

        $this->assertTrue($canDeploy);
    }

    public function test_can_deploy_negative()
    {
        $sdk = $this->getSdkWithMockedClient(
            403,
            null,
            'get',
            'limits/can_deploy'
        );

        $canDeploy = $sdk->canDeployOffer('175e7781-a186-47ed-91a7-b24e94b8e5c2');

        $this->assertFalse($canDeploy);
    }
}
