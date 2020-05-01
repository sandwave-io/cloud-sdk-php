<?php

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use SandwaveIo\CloudSdkPhp\Exceptions\CloudHttpException;
use SandwaveIo\CloudSdkPhp\CloudSdk;
use SandwaveIo\CloudSdkPhp\Client\APIClient;
use SandwaveIo\CloudSdkPhp\Support\UserDataFactory;

class ServerTest extends AbstractCloudSdkCase
{
    public function test_list_servers()
    {
        $sdk = $this->getSdkWithMockedClient(
            'get',
            200,
            '{"data": [{"foo":"bar"},{"foo":"baz"}]}'
        );

        $json = $sdk->listServers();

        $this->assertIsArray($json);
        $this->assertEquals(2, count($json));
        $this->assertEquals(['foo' => 'bar'], $json[0]);
    }

    public function test_show_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            'get',
            200,
            '{"data": [{"foo":"bar"}]}'
        );

        $json = $sdk->showServer('eee-ooo-aaa');

        $this->assertIsArray($json);
        $this->assertEquals(1, count($json));
        $this->assertEquals(['foo' => 'bar'], $json[0]);
    }

    public function test_console_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            'get',
            200,
            '{"data": [{"url":"bar"}]}'
        );

        $json = $sdk->getConsoleUrl('eee-ooo-aaa');

        $this->assertIsArray($json);
        $this->assertEquals(1, count($json));
        $this->assertEquals(['url' => 'bar'], $json[0]);
    }

    public function test_details_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            'get',
            200,
            '{"data": [{"foo":"bar"}]}'
        );

        $json = $sdk->showDetails('eee-ooo-aaa');

        $this->assertIsArray($json);
        $this->assertEquals(1, count($json));
        $this->assertEquals(['foo' => 'bar'], $json[0]);
    }

    public function test_create_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            'post',
            201,
            '{"data": {"id":"aaa-bbb-ccc-ddd"}}'
        );

        $json = $sdk->createServer('test.io', 'joee', '1', '1', '1', []);

        $this->assertIsArray($json);
    }

    public function test_create_server_negative()
    {
        $this->expectException(CloudHttpException::class);
        $guzzle = Mockery::mock(Client::class);
        $client = new APIClient('a', 'b', $guzzle);
        $sdk    = new CloudSdk('a', 'b', new UserDataFactory, $client);

        $guzzle->shouldReceive('post')->once()->andReturn(new Response(422, [], '{}'));

        $this->expectException(CloudHttpException::class);
        $sdk->createServer('test.io', 'joee', 1, '1', '1', []);
    }

    public function test_detach_rescue_iso_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            'post',
            201,
            '{"data": []}'
        );

        $json = $sdk->detachRescueIso('eeee-aaaa-uuuu');

        $this->assertIsArray($json);
    }

    public function test_attach_rescue_iso_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            'post',
            201,
            '{"data": []}'
        );

        $json = $sdk->attachRescueIso('eeee-aaaa-uuuu');

        $this->assertIsArray($json);
    }

    public function test_reboot_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            'post',
            204,
            '{"data": []}'
        );

        $json = $sdk->rebootServer('eeee-aaaa-uuuu');

        $this->assertIsArray($json);
    }

    public function test_start_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            'post',
            204,
            '{"data": []}'
        );

        $json = $sdk->startServer('eeee-aaaa-uuuu');

        $this->assertIsArray($json);
    }

    public function test_stop_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            'post',
            204,
            '{"data": []}'
        );

        $json = $sdk->stopServer('eeee-aaaa-uuuu');

        $this->assertIsArray($json);
    }

    public function test_upgrade_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            'patch',
            204,
            '{"data": []}'
        );

        $json = $sdk->upgradeServer('eeee-aaaa-bbbb', '33');

        $this->assertIsArray($json);
    }

    public function test_delete_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            'delete',
            204,
            '{"data": []}'
        );

        $json = $sdk->deleteServer('eeee-aaaa-bbbb');

        $this->assertIsArray($json);
    }
}
