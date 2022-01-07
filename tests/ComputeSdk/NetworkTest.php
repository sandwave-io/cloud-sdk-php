<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\ComputeSdk;

final class NetworkTest extends AbstractComputeSdkCase
{
    public function test_list_templates(): void
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/network_list.json',
            'get',
            'networks'
        );

        $networks = $sdk->listNetworks();

        self::assertNotSame(0, $networks->count());

        self::assertSame('man.zone03.ams02.cldin.net', $networks->current()?->getManager());
    }
}
