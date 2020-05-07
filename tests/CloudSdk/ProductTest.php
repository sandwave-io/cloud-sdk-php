<?php

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use SandwaveIo\CloudSdkPhp\Domain\OfferCollection;
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
            'filter%5Bcategory%5D=compute_servers&include=categories&limit=50&page=1&account_id=00000000-0000-0000-0000-000000000000'
        );

        $listOffers = $sdk->listOffers();

        $this->assertInstanceOf(OfferCollection::class, $listOffers);
        $this->assertNotEquals(0, $listOffers->count());

        $skus = [];
        foreach ($listOffers as $listOffer) {
            $skus[] = $listOffer->getSku();
        }
        $this->assertEquals($skus[14], 'compute_ha_32gb');
    }

    public function test_list_server_offers()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/offers_servers_list.json',
            'get',
            '/products/offers',
            'filter%5Bcategory%5D=compute_servers&include=categories&limit=51&page=2&account_id=00000000-0000-0000-0000-000000000000'
        );

        $serverOffers = $sdk->listServerOffers(51, 2);

        $this->assertInstanceOf(OfferCollection::class, $serverOffers);
        $this->assertNotEquals(0, $serverOffers->count());

        $skus = [];
        foreach ($serverOffers as $serverOffer) {
            $skus[] = $serverOffer->getSku();
        }
        $this->assertEquals('compute_ha_32gb', $skus[14]);
    }

    public function test_list_disk_offers()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/offers_disks_list.json',
            'get',
            '/products/offers',
            'filter%5Bcategory%5D=compute_disks&include=categories&limit=51&page=2&account_id=00000000-0000-0000-0000-000000000000'
        );

        $diskOfferCollection = $sdk->listDiskOffers(51, 2);

        $this->assertInstanceOf(OfferCollection::class, $diskOfferCollection);
        $this->assertNotEquals(0, $diskOfferCollection->count());

        $skus = [];
        foreach ($diskOfferCollection as $diskOffer) {
            $skus[] = $diskOffer->getSku();
        }
        $this->assertEquals('compute_ssd_250gb', $skus[1]);
    }
}
