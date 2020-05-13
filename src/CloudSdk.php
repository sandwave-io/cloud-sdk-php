<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp;

use GuzzleHttp\Client;
use SandwaveIo\CloudSdkPhp\Client\APIClient;
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

    /**
     * CloudSdk constructor.
     *
     * @param string               $apiKey
     * @param string               $accountId
     * @param UserDataFactory|null $userDataFactory
     * @param APIClient|null       $client
     */
    public function __construct(string $apiKey, string $accountId, ?UserDataFactory $userDataFactory= null, ?APIClient $client = null)
    {
        $this->userDataFactory = $userDataFactory ?? new UserDataFactory();

        $this->client = $client ?? new APIClient(
            $apiKey,
            $accountId,
            new Client([
                    'base_uri' => 'https://api.pcextreme.nl/v2/compute/',
                ])
        );
    }

    /**
     * @param string        $hostname
     * @param string        $password
     * @param string        $offerId
     * @param string        $templateId
     * @param string        $datacenterId
     * @param array<string> $sshKeys
     *
     * @return array<mixed>
     */
    public function createServer(
        string $hostname,
        string $password,
        string $offerId,
        string $templateId,
        string $datacenterId,
        array $sshKeys
    ) : array {
        return $this->client->post(
            'vms',
            [
                'display_name'  => $hostname,
                'offer_id'      => $offerId,
                'datacenter_id' => $datacenterId,
                'template_id'   => $templateId,
                'user_data'     => $this->userDataFactory->generateUserData($hostname, $password, $sshKeys),
            ]
        );
    }

    /**
     * @param int $limit
     * @param int $page
     *
     * @return array<mixed>
     */
    public function listServers(int $limit = 50, int $page = 1) : array
    {
        return $this->client->get(
            'vms',
            [
                'include' => 'offer,datacenter',
                'limit'           => $limit,
                'page'            => $page,
            ]
        );
    }

    /**
     * @param string $id UUID of server.
     *
     * @return array<mixed>
     */
    public function showServer(string $id) : array
    {
        return $this->client->get(
            "vms/{$id}",
            [
                'include' => 'offer,datacenter,disks.offer',
            ]
        );
    }

    /**
     * @param string $id      UUID of server.
     * @param string $offerId UUID of offer, use listOffers to acquire.
     *
     * @return array<mixed>
     */
    public function upgradeServer(string $id, string $offerId) : array
    {
        return $this->client->patch(
            "vms/{$id}",
            [
                'offer_id' => $offerId,
            ]
        );
    }

    /**
     * @param string $id UUID of server.
     *
     * @return array<mixed>
     */
    public function getConsoleUrl(string $id) : array
    {
        return $this->client->get(
            "vms/{$id}/console"
        );
    }

    /**
     * @param string $id UUID of server.
     *
     * @return array<mixed>
     */
    public function detachRescueIso(string $id) : array
    {
        return $this->client->post("vms/{$id}/detachRescue", [], [], 204);
    }

    /**
     * @param string $id UUID of server.
     *
     * @return array<mixed>
     */
    public function attachRescueIso(string $id) : array
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
     * @param string $id UUID of server.
     *
     * @return array<mixed>
     */
    public function rebootServer(string $id) : array
    {
        return $this->client->post("vms/{$id}/reboot", [], [], 204);
    }

    /**
     * @param string $id UUID of server.
     *
     * @return array<mixed>
     */
    public function stopServer(string $id) : array
    {
        return $this->client->post("vms/{$id}/stop", [], [], 204);
    }

    /**
     * @param string $id UUID of server.
     *
     * @return array<mixed>
     */
    public function startServer(string $id) : array
    {
        return $this->client->post("vms/{$id}/start", [], [], 204);
    }

    /**
     * @param string $id UUID of server.
     *
     * @return array<mixed>
     */
    public function deleteServer(string $id) : array
    {
        return $this->client->delete("vms/{$id}", [], 204);
    }

    /**
     * @deprecated Some data from this endpoint will be added to the showServer endpoint.
     *
     * @param string $id UUID of server.
     *
     * @return array<mixed>
     */
    public function showDetails(string $id) : array
    {
        return $this->client->get("vms/{$id}/details");
    }

    /**
     * Retrieve current resource usage of the account.
     *
     * @return array<mixed>
     */
    public function getUsage()
    {
        return $this->client->get('limits/current_usage');
    }

    /**
     * Check if an offer can be deployed under the account.
     *
     * @param string $offerId
     *
     * @return bool
     */
    public function canDeployOffer(string $offerId) : bool
    {
        try {
            $this->client->get('limits/can_deploy', [], 204);
            return true;
        } catch (CloudHttpException $e) {
            return false;
        }
    }

    /**
     * List offers available for server deployments.
     *
     * @deprecated Use listServerOffers or listDiskOffers instead.
     *
     * @return array<mixed>
     */
    public function listOffers() : array
    {
        return $this->client->get('/products/offers', [
            'filter[category]'  => 'compute_servers',
            'include'           => 'categories',
            'limit'             => 50,
            'page'              => 1,
        ]);
    }

    /**
     * List offers available for server deployments.
     *
     * @param int $limit
     * @param int $page
     *
     * @return array<mixed>
     */
    public function listServerOffers(int $limit = 50, int $page = 1) : array
    {
        return $this->client->get('/products/offers', [
            'filter[category]'  => 'compute_servers',
            'include'           => 'categories',
            'limit'             => $limit,
            'page'              => $page,
        ]);
    }

    /**
     * List offers available for disk deployments.
     *
     * @param int $limit
     * @param int $page
     *
     * @return array<mixed>
     */
    public function listDiskOffers(int $limit = 50, int $page = 1) : array
    {
        return $this->client->get('/products/offers', [
            'filter[category]'  => 'compute_disks',
            'include'           => 'categories',
            'limit'             => $limit,
            'page'              => $page,
        ]);
    }

    /**
     * List datacenters available for server deployments.
     *
     * @return array<mixed>
     */
    public function listDatacenters() : array
    {
        return $this->client->get('datacenters');
    }

    /**
     * List templates available for server deployments.
     *
     * @return array<mixed>
     */
    public function listTemplates() : array
    {
        return $this->client->get('templates');
    }

    /**
     * List networks available for server deployments.
     *
     * @return array<mixed>
     */
    public function listNetworks() : array
    {
        return $this->client->get('networks');
    }
}
