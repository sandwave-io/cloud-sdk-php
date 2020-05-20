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
        $this->assertNull($server->getIPv4Address());
        $this->assertNull($server->getIPv6Address());
        $this->assertNull($server->getNetworkId());
    }

    public function test_show_server_detailed()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/server_show_detailed.json',
            'get',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4',
            'include=offer%2Cdatacenter%2Cdisks.offer&account_id=00000000-0000-0000-0000-000000000000'
        );

        $server = $sdk->showServer(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));

        $this->assertInstanceOf(Server::class, $server);
        $this->assertSame('Running', (string) $server->getStatus());
        $this->assertSame('185.109.216.103', $server->getIPv4Address());
        $this->assertSame('2a05:1500:600:0:1c00:83ff:fe00:63', $server->getIPv6Address());
        $this->assertSame('a0234ec3-bdb6-46b8-80a1-37144d7c3928', (string) $server->getNetworkId());
    }

    public function test_console_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/server_console.json',
            'get',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/console'
        );

        $url = $sdk->getConsoleUrl(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));

        $this->assertSame(
            'https://console.auroracompute.eu/ams3?apikey=hidden&cmd=hidden&sessionkey=hidden&timestamp=nidden&userid=hidden&vm=hidden&signature=hidden%3D',
            $url
        );
    }

    public function test_console_server_throwsException() : void
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/server_console_no_url.json',
            'get',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/console'
        );

        $this->expectException(CloudHttpException::class);
        $sdk->getConsoleUrl(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));
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
        $this->assertSame('2f811c1b-3bf5-4592-b7b5-00ff80f43968', (string) $serverId);
        $this->assertSame(
            '2f811c1b-3bf5-4592-b7b5-00ff80f43968',
            (string) $serverId
        );
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

    public function test_create_server_throwsException_noId() : void
    {
        $sdk = $this->getSdkWithMockedClient(
            201,
            'json/server_create_no_id.json',
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

        $sdk->attachRescueIso(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));
    }

    public function test_detach_rescue_iso_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'post',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/detachRescue'
        );

        $sdk->detachRescueIso(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));
    }

    public function test_reboot_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'post',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/reboot'
        );

        $sdk->rebootServer(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));
    }

    public function test_start_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'post',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/start'
        );

        $sdk->startServer(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));
    }

    public function test_stop_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'post',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/stop'
        );

        $sdk->stopServer(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));
    }

    public function test_upgrade_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'patch',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4'
        );

        $sdk->upgradeServer(
            ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'),
            OfferId::fromString('a96bb19a-6289-4b26-a812-3d97d69e4ecb')
        );
    }

    public function test_delete_server()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'delete',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4'
        );

        $sdk->deleteServer(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));
    }
}
