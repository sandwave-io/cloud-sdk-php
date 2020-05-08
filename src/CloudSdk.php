<?php

namespace SandwaveIo\CloudSdkPhp;

use GuzzleHttp\Client;
use SandwaveIo\CloudSdkPhp\Client\APIClient;
use SandwaveIo\CloudSdkPhp\Domain\AccountId;
use SandwaveIo\CloudSdkPhp\Domain\DataCenterCollection;
use SandwaveIo\CloudSdkPhp\Domain\DatacenterId;
use SandwaveIo\CloudSdkPhp\Domain\DiskOfferCollection;
use SandwaveIo\CloudSdkPhp\Domain\OfferCollection;
use SandwaveIo\CloudSdkPhp\Domain\OfferId;
use SandwaveIo\CloudSdkPhp\Domain\Server;
use SandwaveIo\CloudSdkPhp\Domain\ServerCollection;
use SandwaveIo\CloudSdkPhp\Domain\ServerId;
use SandwaveIo\CloudSdkPhp\Domain\ServerOfferCollection;
use SandwaveIo\CloudSdkPhp\Domain\TemplateCollection;
use SandwaveIo\CloudSdkPhp\Domain\TemplateId;
use SandwaveIo\CloudSdkPhp\Support\UserDataFactory;

final class CloudSdk
{
    /**
     * @var string
     */
    private $baseUrl = 'https://api.pcextreme.nl/v2/compute/';

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
        $this->userDataFactory = $userDataFactory ?? new UserDataFactory;

        $this->client = $client ??
            new APIClient(
                $apiKey,
                $accountId,
                new Client(
                    [
                        'base_uri' => $this->baseUrl,
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
        array $sshKeys
    ): ServerId {
        $data = $this->client->post(
            'vms',
            [
                'display_name' => $hostname,
                'offer_id' => (string) $offerId,
                'datacenter_id' => (string) $datacenterId,
                'template_id' => (string) $templateId,
                'user_data' => $this->userDataFactory->generateUserData($hostname, $password, $sshKeys),
            ]
        );

        return ServerId::fromString($data['id']);
    }

    public function listServers(int $limit = 50, int $page = 1): ServerCollection
    {
        return ServerCollection::fromArray(
            $this->client->get(
                'vms',
                [
                    'include' => 'offer,datacenter',
                    'per_page' => $limit,
                    'page' => $page
                ]
            )
        );
    }

    public function showServer(ServerId $id): Server
    {
        return Server::fromArray(
            $this->client->get(
                "vms/{$id}",
                [
                    'include' => 'offer,datacenter,disks.offer'
                ]
            )
        );
    }

    /**
     * use listOffers to acquire.
     * @return array<mixed>
     */
    public function upgradeServer(ServerId $id, OfferId $offerId): array
    {
        return $this->client->patch(
            "vms/{$id}",
            [
                'offer_id' => $offerId
            ]
        );
    }

    /**
     * @return array<mixed>
     */
    public function getConsoleUrl(ServerId $id): array
    {
        return $this->client->get(
            "vms/{$id}/console"
        );
    }

    /**
     * @return array<mixed>
     */
    public function detachRescueIso(ServerId $id): array
    {
        return $this->client->post("vms/{$id}/detachRescue", [], [], 204);
    }

    /**
     * @return array<mixed>
     */
    public function attachRescueIso(ServerId $id): array
    {
        return $this->client->post("vms/{$id}/attachRescue", [], [], 204);
    }

    /**
     * @return array<mixed>
     */
    public function rebootServer(ServerId $id): array
    {
        return $this->client->post("vms/{$id}/reboot", [], [], 204);
    }

    /**
     * @return array<mixed>
     */
    public function stopServer(ServerId $id): array
    {
        return $this->client->post("vms/{$id}/stop", [], [], 204);
    }

    /**
     * @return array<mixed>
     */
    public function startServer(ServerId $id): array
    {
        return $this->client->post("vms/{$id}/start", [], [], 204);
    }

    /**
     * @return array<mixed>
     */
    public function deleteServer(ServerId $id): array
    {
        return $this->client->delete("vms/{$id}", [], 204);
    }


    /**
     * @deprecated Some data from this endpoint will be added to the showServer endpoint.
     * @return array<mixed>
     */
    public function showDetails(ServerId $id): array
    {
        return $this->client->get("vms/{$id}/details");
    }


    /**
     * Retrieve current resource usage of the account.
     * @return array<mixed>
     */
    public function getUsage()
    {
        return $this->client->get("usage");
    }

    /**
     * List offers available for server deployments.
     * @deprecated Use listServerOffers or listDiskOffers instead.
     */
    public function listOffers(): OfferCollection
    {
        return OfferCollection::fromArray(
            $this->client->get(
                '/products/offers',
                [
                    'filter[category]' => 'compute_servers',
                    'include' => 'categories',
                    'per_page' => 50,
                    'page' => 1
                ]
            )
        );
    }

    /**
     * List offers available for server deployments.
     * @param int $limit
     * @param int $page
     */
    public function listServerOffers(int $limit = 50, int $page = 1): OfferCollection
    {
        return OfferCollection::fromArray(
            $this->client->get(
                '/products/offers',
                [
                    'filter[category]' => 'compute_servers',
                    'include' => 'categories',
                    'per_page' => $limit,
                    'page' => $page
                ]
            )
        );
    }

    /**
     * List offers available for disk deployments.
     * @param int $limit
     * @param int $page
     */
    public function listDiskOffers(int $limit = 50, int $page = 1): OfferCollection
    {
        return OfferCollection::fromArray(
            $this->client->get(
                '/products/offers',
                [
                    'filter[category]' => 'compute_disks',
                    'include' => 'categories',
                    'per_page' => $limit,
                    'page' => $page
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

    /**
     * List networks available for server deployments.
     * @return array<mixed>
     */
    public function listNetworks() : array
    {
        return $this->client->get('networks');
    }
}
