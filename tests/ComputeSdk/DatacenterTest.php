<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\ComputeSdk;

use SandwaveIo\CloudSdkPhp\Domain\Compute\DataCenterCollection;

final class DatacenterTest extends AbstractComputeSdkCase
{
    public function test_list_datacenters(): void
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/datacenter_list.json',
            'get',
            'datacenters'
        );

        $datacenters = $sdk->listDatacenters();

        self::assertInstanceOf(DataCenterCollection::class, $datacenters);
        self::assertNotSame(0, $datacenters->count());

        self::assertSame('ams01', $datacenters->current()?->getName());
    }
}
