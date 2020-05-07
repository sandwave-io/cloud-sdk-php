<?php

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

use SandwaveIo\CloudSdkPhp\Domain\DataCenterCollection;

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

        $datacenters = $sdk->listDatacenters();

        $this->assertInstanceOf(DataCenterCollection::class, $datacenters);
        $this->assertNotEquals(0, $datacenters->count());

        $this->assertEquals('ams01', $datacenters->current()->getName());
    }
}
