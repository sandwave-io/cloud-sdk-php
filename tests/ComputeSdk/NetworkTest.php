<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\ComputeSdk;

use SandwaveIo\CloudSdkPhp\Domain\Compute\NetworkCollection;

class NetworkTest extends AbstractComputeSdkCase
{
    public function test_list_templates()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/network_list.json',
            'get',
            'networks'
        );

        $networks = $sdk->listNetworks();

        $this->assertInstanceOf(NetworkCollection::class, $networks);
        $this->assertNotSame(0, $networks->count());

        $this->assertSame('man.zone03.ams02.cldin.net', $networks->current()->getManager());
    }
}
