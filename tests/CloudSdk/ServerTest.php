<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

use SandwaveIo\CloudSdkPhp\Domain\DatacenterId;
use SandwaveIo\CloudSdkPhp\Domain\NetworkId;
use SandwaveIo\CloudSdkPhp\Domain\OfferId;
use SandwaveIo\CloudSdkPhp\Domain\Server;
use SandwaveIo\CloudSdkPhp\Domain\ServerCollection;
use SandwaveIo\CloudSdkPhp\Domain\ServerId;
use SandwaveIo\CloudSdkPhp\Domain\TemplateId;
use SandwaveIo\CloudSdkPhp\Exceptions\CloudHttpException;

class ServerTest extends AbstractCloudSdkCase
{
    public function test_list_servers()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/server_list.json',
            'get',
            'vms',
            'include=offer%2Cdatacenter&per_page=51&page=2&account_id=00000000-0000-0000-0000-000000000000'
        );

        $serverlist = $sdk->listServers(51, 2);

        $this->assertInstanceOf(ServerCollection::class, $serverlist);
        $this->assertSame(2, $serverlist->count());
        $this->assertSame('Running', (string) $serverlist->current()->getStatus());
    }

    public function test_show_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/server_show.json',
            'get',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4',
            'include=offer%2Cdatacenter%2Cdisks.offer&account_id=00000000-0000-0000-0000-000000000000'
        );

        $server = $sdk->showServer(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));

        $this->assertInstanceOf(Server::class, $server);
        $this->assertSame('Running', (string) $server->getStatus());
    }

    public function test_console_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/server_console.json',
            'get',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/console'
        );

        $json = $sdk->getConsoleUrl(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));

        $this->assertTrue(is_array($json));
        $this->assertNotSame([], $json);
        $this->assertArrayContains('url', 'https://console.auroracompute.eu/ams3?apikey=hidden&cmd=hidden&sessionkey=hidden&timestamp=nidden&userid=hidden&vm=hidden&signature=hidden%3D', $json);
    }

    public function test_details_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/server_details.json',
            'get',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/details'
        );

        $json = $sdk->showDetails(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));

        $this->assertTrue(is_array($json));
        $this->assertNotSame([], $json);
        $this->assertArrayContains('ipaddress', '185.109.216.103', $json);
    }

    public function test_create_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            201,
            'json/server_create.json',
            'post',
            'vms'
        );

        $serverId = $sdk->createServer(
            'test.example.com',
            'Admin123',
            OfferId::fromString('8cbfe407-1cbc-49ea-b7a2-c4e6fd147474'),
            TemplateId::fromString('8b38ce30-485b-4610-bb85-1bf02299cbc5'),
            DatacenterId::fromString('36616598-8e93-4118-a03c-94f99e5e1169'),
            NetworkId::fromString('36616598-8e93-4118-a03c-94f99e5e1169'),
            []
        );

        $this->assertInstanceOf(ServerId::class, $serverId);
        $this->assertSame(ServerId::fromString('2f811c1b-3bf5-4592-b7b5-00ff80f43968'), $serverId);
    }

    public function test_create_server_negative()
    {
        $sdk = $this->getSdkWithMockedClient(
            422,
            'json/server_create.json',
            'post',
            'vms'
        );

        $this->expectException(CloudHttpException::class);
        $json = $sdk->createServer(
            'test.example.com',
            'Admin123',
            OfferId::fromString('8cbfe407-1cbc-49ea-b7a2-c4e6fd147474'),
            TemplateId::fromString('8b38ce30-485b-4610-bb85-1bf02299cbc5'),
            DatacenterId::fromString('36616598-8e93-4118-a03c-94f99e5e1169'),
            NetworkId::fromString('36616598-8e93-4118-a03c-94f99e5e1169'),
            []
        );
    }

    public function test_attach_rescue_iso_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'post',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/attachRescue'
        );

        $json = $sdk->attachRescueIso(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));

        $this->assertTrue(is_array($json));
        $this->assertSame([], $json);
    }

    public function test_detach_rescue_iso_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'post',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/detachRescue'
        );

        $json = $sdk->detachRescueIso(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));

        $this->assertTrue(is_array($json));
        $this->assertSame([], $json);
    }

    public function test_reboot_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'post',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/reboot'
        );

        $json = $sdk->rebootServer(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));

        $this->assertTrue(is_array($json));
        $this->assertSame([], $json);
    }

    public function test_start_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'post',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/start'
        );

        $json = $sdk->startServer(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));

        $this->assertTrue(is_array($json));
        $this->assertSame([], $json);
    }

    public function test_stop_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'post',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/stop'
        );

        $json = $sdk->stopServer(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));

        $this->assertTrue(is_array($json));
        $this->assertSame([], $json);
    }

    public function test_upgrade_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'patch',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4'
        );

        $json = $sdk->upgradeServer(
            ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'),
            OfferId::fromString('a96bb19a-6289-4b26-a812-3d97d69e4ecb')
        );

        $this->assertTrue(is_array($json));
        $this->assertSame([], $json);
    }

    public function test_delete_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'delete',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4'
        );

        $json = $sdk->deleteServer(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));

        $this->assertTrue(is_array($json));
        $this->assertSame([], $json);
    }
}
