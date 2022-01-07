<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\ComputeSdk;

use SandwaveIo\CloudSdkPhp\Domain\Compute\DiskId;
use SandwaveIo\CloudSdkPhp\Domain\Compute\ServerId;
use SandwaveIo\CloudSdkPhp\Domain\OfferId;
use SandwaveIo\CloudSdkPhp\Exceptions\CloudHttpException;

final class DiskTest extends AbstractComputeSdkCase
{
    public function test_list_disks(): void
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/disk_list.json',
            'get',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/disks'
        );

        $diskList = $sdk->listDisks(ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'));

        self::assertSame(1, $diskList->count());
        self::assertSame('test', (string) $diskList->current()?->getDisplayName());
    }

    public function test_create_disk(): void
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

        self::assertSame(
            '4031dfb0-a9a1-499b-8707-7f126bfcbda6',
            (string) $id
        );
    }

    public function test_create_disk_throwsException(): void
    {
        $sdk = $this->getSdkWithMockedClient(
            201,
            'json/disk_create_no_id.json',
            'post',
            'vms/6a6256cc-e6ff-41d2-9894-95a066d2b7a4/disks'
        );

        $this->expectException(CloudHttpException::class);
        $sdk->createDisk(
            ServerId::fromString('6a6256cc-e6ff-41d2-9894-95a066d2b7a4'),
            OfferId::fromString('175e7781-a186-47ed-91a7-b24e94b8e5c2'),
            'test'
        );
    }

    public function test_delete_disk(): void
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
