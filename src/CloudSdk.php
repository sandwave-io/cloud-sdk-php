<?php /** @noinspection ALL */
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp;

use GuzzleHttp\Client;
use SandwaveIo\CloudSdkPhp\Client\APIClient;
use SandwaveIo\CloudSdkPhp\Domain\AccountId;
use SandwaveIo\CloudSdkPhp\Domain\DataCenterCollection;
use SandwaveIo\CloudSdkPhp\Domain\DatacenterId;
use SandwaveIo\CloudSdkPhp\Domain\DiskCollection;
use SandwaveIo\CloudSdkPhp\Domain\DiskId;
use SandwaveIo\CloudSdkPhp\Domain\NetworkCollection;
use SandwaveIo\CloudSdkPhp\Domain\NetworkId;
use SandwaveIo\CloudSdkPhp\Domain\OfferCollection;
use SandwaveIo\CloudSdkPhp\Domain\OfferId;
use SandwaveIo\CloudSdkPhp\Domain\Server;
use SandwaveIo\CloudSdkPhp\Domain\ServerCollection;
use SandwaveIo\CloudSdkPhp\Domain\ServerId;
use SandwaveIo\CloudSdkPhp\Domain\TemplateCollection;
use SandwaveIo\CloudSdkPhp\Domain\TemplateId;
use SandwaveIo\CloudSdkPhp\Domain\Usage;
use SandwaveIo\CloudSdkPhp\Exceptions\CloudHttpException;
use SandwaveIo\CloudSdkPhp\Support\UserDataFactory;

final class CloudSdk
{
    /**
     * @var APIClient
     */
    private $client;

    /**
     * @var UserDataFactory
     */
    private $userDataFactory;

    public function __construct(
        string $apiKey,
        AccountId $accountId,
        ?UserDataFactory $userDataFactory = null,
        ?APIClient $client = null
    ) {
        $this->userDataFactory = $userDataFactory ?? new UserDataFactory();

        $this->client = $client ??
            new APIClient(
                $apiKey,
                $accountId,
                new Client(
                    [
                        'base_uri' => APIClient::BASE_URL,
                    ]
                )
            );
    }

    /**
     * @param array<string> $sshKeys
     */
    public function createServer(
        string $hostname,
        string $password,
        OfferId $offerId,
        TemplateId $templateId,
        DatacenterId $datacenterId,
        ?NetworkId $networkId,
        array $sshKeys
    ) : ServerId {
        $postData = [
            'display_name' => $hostname,
            'offer_id' => (string) $offerId,
            'datacenter_id' => (string) $datacenterId,
            'template_id' => (string) $templateId,
            'user_data' => $this->userDataFactory->generateUserData($hostname, $password, $sshKeys),
        ];

        if ($networkId instanceof NetworkId) {
            $postData['network_id'] = (string) $networkId;
        }

        $data = $this->client->post('vms', $postData);

        if (! array_key_exists('id', $data)) {
            throw new CloudHttpException('Could not resolve ID of created server.');
        }

        return ServerId::fromString($data['id']);
    }

    public function listServers(int $limit = 50, int $page = 1) : ServerCollection
    {
        return ServerCollection::fromArray(
            $this->client->get(
                'vms',
                [
                    'include' => 'offer,datacenter',
                    'per_page' => $limit,
                    'page' => $page,
                ]
            )
        );
    }

    public function showServer(ServerId $id) : Server
    {
        return Server::fromArray(
            $this->client->get(
                "vms/{$id}",
                [
                    'include' => 'offer,datacenter,disks.offer',
                ]
            )
        );
    }

    public function upgradeServer(ServerId $id, OfferId $offerId) : void
    {
        $this->client->patch(
            "vms/{$id}",
            [
                'offer_id' => (string) $offerId,
            ]
        );
    }

    public function getConsoleUrl(ServerId $id) : string
    {
        $data = $this->client->get(
            "vms/{$id}/console"
        );

        if (! array_key_exists('url', $data)) {
            throw new CloudHttpException('Could not get console URL.');
        }

        return $data['url'];
    }

    public function detachRescueIso(ServerId $id) : void
    {
        $this->client->post("vms/{$id}/detachRescue", [], [], 204);
    }

    public function attachRescueIso(ServerId $id) : void
    {
        $this->client->post("vms/{$id}/attachRescue", [], [], 204);
    }

    public function createDisk(ServerId $serverId, OfferId $offerId, string $displayName) : DiskId
    {
        $data = $this->client->post(
            "vms/{$serverId}/disks",
            [
                'offer_id' => (string) $offerId,
                'display_name' => $displayName,
            ],
            [],
            201
        );

        if (! array_key_exists('id', $data)) {
            throw new CloudHttpException('Could not resolve ID of created disk.');
        }

        return DiskId::fromString($data['id']);
    }

    public function listDisks(ServerId $serverId) : DiskCollection
    {
        return DiskCollection::fromArray(
            $this->client->get("vms/{$serverId}/disks")
        );
    }

    public function deleteDisk(ServerId $serverId, DiskId $diskId) : void
    {
        $this->client->delete("vms/{$serverId}/disks/{$diskId}", [], 204);
    }

    public function rebootServer(ServerId $id) : void
    {
        $this->client->post("vms/{$id}/reboot", [], [], 204);
    }

    public function stopServer(ServerId $id) : void
    {
        $this->client->post("vms/{$id}/stop", [], [], 204);
    }

    public function startServer(ServerId $id) : void
    {
        $this->client->post("vms/{$id}/start", [], [], 204);
    }

    public function deleteServer(ServerId $id) : void
    {
        $this->client->delete("vms/{$id}", [], 204);
    }

    /**
     * @deprecated Some data from this endpoint will be added to the showServer endpoint.
     *
     * @return array<mixed>
     */
    public function showDetails(ServerId $id) : array
    {
        return $this->client->get("vms/{$id}/details");
    }

    /**
     * Retrieve current resource usage of the account.
     */
    public function getUsage() : Usage
    {
        return Usage::fromArray(
            $this->client->get('limits/current_usage')
        );
    }

    /**
     * Check if an offer can be deployed under the account.
     */
    public function canDeployOffer(OfferId $offerId) : bool
    {
        try {
            $this->client->get(
                'limits/can_deploy',
                [
                    'offer_id' => (string) $offerId,
                ],
                204
            );

            return true;
        } catch (CloudHttpException $e) {
            return false;
        }
    }

    /**
     * List offers available for server deployments.
     *
     * @deprecated Use listServerOffers or listDiskOffers instead.
     */
    public function listOffers() : OfferCollection
    {
        return OfferCollection::fromArray(
            $this->client->get(
                '/products/offers',
                [
                    'filter[category]' => 'compute_servers',
                    'include' => 'categories',
                    'per_page' => 50,
                    'page' => 1,
                ]
            )
        );
    }

    /**
     * List offers available for server deployments.
     */
    public function listServerOffers(int $limit = 50, int $page = 1) : OfferCollection
    {
        return OfferCollection::fromArray(
            $this->client->get(
                '/products/offers',
                [
                    'filter[category]' => 'compute_servers',
                    'include' => 'categories',
                    'per_page' => $limit,
                    'page' => $page,
                ]
            )
        );
    }

    /**
     * List offers available for disk deployments.
     */
    public function listDiskOffers(int $limit = 50, int $page = 1) : OfferCollection
    {
        return OfferCollection::fromArray(
            $this->client->get(
                '/products/offers',
                [
                    'filter[category]' => 'compute_disks',
                    'include' => 'categories',
                    'per_page' => $limit,
                    'page' => $page,
                ]
            )
        );
    }

    public function listDatacenters() : DataCenterCollection
    {
        return DataCenterCollection::fromArray(
            $this->client->get('datacenters')
        );
    }

    public function listTemplates() : TemplateCollection
    {
        return TemplateCollection::fromArray(
            $this->client->get('templates')
        );
    }

    public function listNetworks() : NetworkCollection
    {
        return NetworkCollection::fromArray(
            $this->client->get('networks')
        );
    }
}
