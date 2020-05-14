<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp;

use GuzzleHttp\Client;
use SandwaveIo\CloudSdkPhp\Client\APIClient;
use SandwaveIo\CloudSdkPhp\Domain\AccountId;
use SandwaveIo\CloudSdkPhp\Domain\DataCenterCollection;
use SandwaveIo\CloudSdkPhp\Domain\DatacenterId;
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

    /**
     * CloudSdk constructor.
     *
     * @param string               $apiKey
     * @param AccountId            $accountId
     * @param UserDataFactory|null $userDataFactory
     * @param APIClient|null       $client
     */
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
     * @param string         $hostname
     * @param string         $password
     * @param OfferId        $offerId
     * @param TemplateId     $templateId
     * @param DatacenterId   $datacenterId
     * @param NetworkId|null $networkId
     * @param array<string>  $sshKeys
     *
     * @return array<mixed>
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

        return ServerId::fromString($data['id']);
    }

    /**
     * @param int $limit
     * @param int $page
     *
     * @return ServerCollection
     */
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

    /**
     * @param ServerId $id
     *
     * @return Server
     */
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

    /**
     * @param ServerId $id
     * @param OfferId  $offerId
     *
     * @return array<mixed>
     */
    public function upgradeServer(ServerId $id, OfferId $offerId) : array
    {
        return $this->client->patch(
            "vms/{$id}",
            [
                'offer_id' => (string) $offerId,
            ]
        );
    }

    /**
     * @param ServerId $id
     *
     * @return array<mixed>
     */
    public function getConsoleUrl(ServerId $id) : array
    {
        return $this->client->get(
            "vms/{$id}/console"
        );
    }

    /**
     * @param ServerId $id
     *
     * @return array<mixed>
     */
    public function detachRescueIso(ServerId $id) : array
    {
        return $this->client->post("vms/{$id}/detachRescue", [], [], 204);
    }

    /**
     * @param ServerId $id
     *
     * @return array<mixed>
     */
    public function attachRescueIso(ServerId $id) : array
    {
        return $this->client->post("vms/{$id}/attachRescue", [], [], 204);
    }

    /**
     * @param string $serverId    Server UUID.
     * @param string $offerId     UUID of disk offer.
     * @param string $displayName A friendly name for the disk.
     *
     * @return array<mixed>
     */
    public function createDisk(string $serverId, string $offerId, string $displayName) : array
    {
        return $this->client->post(
            "vms/{$serverId}/disks",
            [
                'offer_id' => $offerId,
                'display_name' => $displayName,
            ],
            [],
            201
        );
    }

    /**
     * @param string $serverId UUID of server.
     *
     * @return array<mixed>
     */
    public function listDisks(string $serverId) : array
    {
        return $this->client->get("vms/{$serverId}/disks");
    }

    /**
     * @param string $serverId UUID of server.
     * @param string $diskId   UUID of disk.
     *
     * @return array<mixed>
     */
    public function deleteDisk(string $serverId, string $diskId) : array
    {
        return $this->client->delete("vms/{$serverId}/disks/{$diskId}", [], 204);
    }

    /**
     * @param ServerId $id
     *
     * @return array<mixed>
     */
    public function rebootServer(ServerId $id) : array
    {
        return $this->client->post("vms/{$id}/reboot", [], [], 204);
    }

    /**
     * @param ServerId $id
     *
     * @return array<mixed>
     */
    public function stopServer(ServerId $id) : array
    {
        return $this->client->post("vms/{$id}/stop", [], [], 204);
    }

    /**
     * @param ServerId $id
     *
     * @return array<mixed>
     */
    public function startServer(ServerId $id) : array
    {
        return $this->client->post("vms/{$id}/start", [], [], 204);
    }

    /**
     * @param ServerId $id
     *
     * @return array<mixed>
     */
    public function deleteServer(ServerId $id) : array
    {
        return $this->client->delete("vms/{$id}", [], 204);
    }

    /**
     * @deprecated Some data from this endpoint will be added to the showServer endpoint.
     *
     * @param ServerId $id
     *
     * @return array<mixed>
     */
    public function showDetails(ServerId $id) : array
    {
        return $this->client->get("vms/{$id}/details");
    }

    /**
     * Retrieve current resource usage of the account.
     *
     * @return Usage
     */
    public function getUsage() : Usage
    {
        return Usage::fromArray(
            $this->client->get('limits/current_usage')
        );
    }

    /**
     * List offers available for server deployments.
     *
     * @deprecated Use listServerOffers or listDiskOffers instead.
     *
     * @return OfferCollection
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
     *
     * @param int $limit
     * @param int $page
     *
     * @return OfferCollection
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
     *
     * @param int $limit
     * @param int $page
     *
     * @return OfferCollection
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

    /**
     * List datacenters available for server deployments.
     *
     * @return DataCenterCollection
     */
    public function listDatacenters() : DataCenterCollection
    {
        return DataCenterCollection::fromArray(
            $this->client->get('datacenters')
        );
    }

    /**
     * List templates available for server deployments.
     *
     * @return TemplateCollection
     */
    public function listTemplates() : TemplateCollection
    {
        return TemplateCollection::fromArray(
            $this->client->get('templates')
        );
    }

    /**
     * List networks available for server deployments.
     *
     * @return NetworkCollection
     */
    public function listNetworks() : NetworkCollection
    {
        return NetworkCollection::fromArray(
            $this->client->get('networks')
        );
    }
}
