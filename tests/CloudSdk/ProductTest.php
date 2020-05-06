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
            200,
            'json/usage.json',
            'get',
            'usage'
        );

        $json = $sdk->getUsage();

        $this->assertTrue(is_array($json));
        $this->assertNotEquals([], $json);
        $this->assertArrayContains('ram', 58, $json);
    }

    public function test_list_offers()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/offers_list.json',
            'get',
            '/products/offers',
            'filter%5Bcategory%5D=compute_servers&include=categories&limit=50&page=1&account_id=this-is-my-account-id'
        );

        $json = $sdk->listOffers();

        $this->assertTrue(is_array($json));
        $this->assertNotEquals([], $json);
        $this->assertArrayContains('sku', 'compute_ha_32gb', $json);
    }

    public function test_list_server_offers()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/offers_servers_list.json',
            'get',
            '/products/offers',
            'filter%5Bcategory%5D=compute_servers&include=categories&limit=51&page=2&account_id=this-is-my-account-id'
        );

        $json = $sdk->listServerOffers(51, 2);

        $this->assertTrue(is_array($json));
        $this->assertNotEquals([], $json);
        $this->assertArrayContains('sku', 'compute_ha_32gb', $json);
    }

    public function test_list_disk_offers()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/offers_disks_list.json',
            'get',
            '/products/offers',
            'filter%5Bcategory%5D=compute_disks&include=categories&limit=51&page=2&account_id=this-is-my-account-id'
        );

        $json = $sdk->listDiskOffers(51, 2);

        $this->assertTrue(is_array($json));
        $this->assertNotEquals([], $json);

        $this->assertArrayContains('sku', 'compute_ssd_250gb', $json);
    }
}
