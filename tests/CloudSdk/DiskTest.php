<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

use SandwaveIo\CloudSdkPhp\Domain\DiskId;
use SandwaveIo\CloudSdkPhp\Domain\OfferId;
use SandwaveIo\CloudSdkPhp\Domain\ServerId;

class DiskTest extends AbstractCloudSdkCase
{
    public function test_list_disks()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/disk_list.json',
            'get',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/disks'
        );

        $json = $sdk->listDisks(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));

        $this->assertTrue(is_array($json));
        $this->assertNotSame([], $json);
        $this->assertArrayContains('display_name', 'test', $json);
    }

    public function test_create_disk()
    {
        $sdk = $this->getSdkWithMockedClient(
            201,
            'json/disk_create.json',
            'post',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/disks'
        );

        $id = $sdk->createDisk(
            ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'),
            OfferId::fromString('175e7781-a186-47ed-91a7-b24e94b8e5c2'),
            'test'
        );

        $this->assertInstanceOf(DiskId::class, $id);
        $this->assertSame(
            '4031dfb0-a9a1-499b-8707-7f126bfcbda6',
            (string) $id
        );
    }

    public function test_delete_disk()
    {
        $sdk = $this->getSdkWithMockedClient(
            204,
            null,
            'delete',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/disks/4031dfb0-a9a1-499b-8707-7f126bfcbda6'
        );

        $sdk->deleteDisk(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'), DiskId::fromString('4031dfb0-a9a1-499b-8707-7f126bfcbda6'));
    }
}
