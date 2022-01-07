<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\ComputeSdk;

use SandwaveIo\CloudSdkPhp\Domain\OfferCollection;

final class ProductTest extends AbstractComputeSdkCase
{
    public function test_list_offers(): void
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/offers_list.json',
            'get',
            '/products/offers',
            'filter%5Bcategory%5D=compute_servers&include=categories&per_page=50&page=1&account_id=00000000-0000-0000-0000-000000000000'
        );

        $listOffers = $sdk->listOffers();

        self::assertNotSame(0, $listOffers->count());

        $skus = [];
        foreach ($listOffers as $listOffer) {
            $skus[] = $listOffer?->getSku();
        }
        self::assertSame($skus[14], 'compute_ha_32gb');
    }

    public function test_list_server_offers(): void
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/offers_servers_list.json',
            'get',
            '/products/offers',
            'filter%5Bcategory%5D=compute_servers&include=categories&per_page=51&page=2&account_id=00000000-0000-0000-0000-000000000000'
        );

        $serverOffers = $sdk->listServerOffers(51, 2);

        self::assertNotSame(0, $serverOffers->count());

        $skus = [];
        foreach ($serverOffers as $serverOffer) {
            $skus[] = $serverOffer?->getSku();
        }
        self::assertSame('compute_ha_32gb', $skus[14]);
    }

    public function test_list_disk_offers(): void
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/offers_disks_list.json',
            'get',
            '/products/offers',
            'filter%5Bcategory%5D=compute_disks&include=categories&per_page=51&page=2&account_id=00000000-0000-0000-0000-000000000000'
        );

        $diskOfferCollection = $sdk->listDiskOffers(51, 2);

        self::assertInstanceOf(OfferCollection::class, $diskOfferCollection);
        self::assertNotSame(0, $diskOfferCollection->count());

        $skus = [];
        foreach ($diskOfferCollection as $diskOffer) {
            $skus[] = $diskOffer?->getSku();
        }
        self::assertSame('compute_ssd_250gb', $skus[1]);
    }
}
