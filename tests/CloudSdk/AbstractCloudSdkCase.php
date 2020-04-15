<?php

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use SandwaveIo\CloudSdkPhp\CloudSdk;
use SandwaveIo\CloudSdkPhp\Client\APIClient;
use SandwaveIo\CloudSdkPhp\Support\UserDataFactory;
use PHPUnit\Framework\TestCase;

class AbstractCloudSdkCase extends TestCase
{
    protected function getSdkWithMockedClient(string $method, int $responseCode, string $responseBody) : CloudSdk
    {
        $guzzle = Mockery::mock(Client::class);
        $client = new APIClient('a', 'b', $guzzle);
        $sdk    = new CloudSdk('a', 'b', new UserDataFactory, $client);

        $guzzle->shouldReceive($method)->once()->andReturn(new Response($responseCode, [], $responseBody));

        return $sdk;
    }
}
