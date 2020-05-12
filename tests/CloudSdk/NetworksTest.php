<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

class NetworksTest extends AbstractCloudSdkCase
{
    public function test_list_templates()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/network_list.json',
            'get',
            'networks'
        );

        $json = $sdk->listNetworks();

        $this->assertTrue(is_array($json));
        $this->assertNotSame([], $json);
        $this->assertArrayContains('manager', 'man.zone03.ams02.cldin.net', $json);
    }
}
