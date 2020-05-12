<?php declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

class DatacenterTest extends AbstractCloudSdkCase
{
    public function test_list_datacenters()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/datacenter_list.json',
            'get',
            'datacenters'
        );

        $json = $sdk->listDatacenters();

        $this->assertTrue(is_array($json));
        $this->assertNotEquals([], $json);
        $this->assertArrayContains('name', 'ams01', $json);
    }
}
