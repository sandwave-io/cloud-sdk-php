<?php

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use SandwaveIo\CloudSdkPhp\Exceptions\CloudHttpException;
use SandwaveIo\CloudSdkPhp\CloudSdk;
use SandwaveIo\CloudSdkPhp\Client\APIClient;
use SandwaveIo\CloudSdkPhp\Support\UserDataFactory;

class ProductTest extends AbstractCloudSdkCase
{
    public function test_usage()
    {
        $sdk = $this->getSdkWithMockedClient(
            'get',
            200,
            '{"data": [{"ram": 1}]}'
        );

        $json = $sdk->getUsage();

        $this->assertIsArray($json);
        $this->assertEquals(1, count($json));
        $this->assertEquals(['ram' => 1], $json[0]);
    }

    public function test_list_offers()
    {
        $sdk = $this->getSdkWithMockedClient(
            'get',
            200,
            '{"data": [{"foo":"bar"}]}'
        );

        $json = $sdk->listOffers();

        $this->assertIsArray($json);
        $this->assertEquals(1, count($json));
        $this->assertEquals(['foo' => 'bar'], $json[0]);
    }
}
