<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\ComputeSdk;

use SandwaveIo\CloudSdkPhp\Domain\Compute\DataCenterCollection;

class DatacenterTest extends AbstractComputeSdkCase
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
        $this->assertNotSame(0, $datacenters->count());

        $this->assertSame('ams01', $datacenters->current()->getName());
    }
}
