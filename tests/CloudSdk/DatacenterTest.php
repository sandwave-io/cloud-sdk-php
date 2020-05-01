<?php

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use SandwaveIo\CloudSdkPhp\Exceptions\CloudHttpException;
use SandwaveIo\CloudSdkPhp\CloudSdk;
use SandwaveIo\CloudSdkPhp\Client\APIClient;
use SandwaveIo\CloudSdkPhp\Support\UserDataFactory;

class DatacenterTest extends AbstractCloudSdkCase
{
    public function test_list_datacenters()
    {
        $sdk = $this->getSdkWithMockedClient(
            'get',
            200,
            '{"data": [{"foo":"bar"}]}'
        );

        $json = $sdk->listDatacenters();

        $this->assertIsArray($json);
        $this->assertEquals(1, count($json));
        $this->assertEquals(['foo' => 'bar'], $json[0]);
    }
}
