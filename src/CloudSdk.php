<?php

namespace SandwaveIo\CloudSdkPhp;

use GuzzleHttp\Client;
use SandwaveIo\CloudSdkPhp\Client\APIClient;
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

    public function __construct(string $apiKey, string $accountId, ?UserDataFactory $userDataFactory= null, ?APIClient $client = null)
    {
        $this->userDataFactory = $userDataFactory ?? new UserDataFactory;

        $this->client = $client ?? new APIClient(
                $apiKey,
                $accountId,
                new Client([
                    'base_uri' => 'https://api.pcextreme.nl/v2/compute/'
                ])
            );
    }

    public function createServer(
        string $hostname,
        string $password,
        string $offerId,
        string $templateId,
        string $datacenterId,
        array $sshKeys
    ) : array
    {
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

    public function listServers() : array
    {
        return $this->client->get(
            'vms',
            [
                'include' => 'offer,datacenter'
            ]
        );
    }

    public function showServer(string $id) : array
    {
        return $this->client->get(
            "vms/{$id}",
            [
                'include' => 'offer,datacenter,disks.offer'
            ]
        );
    }

    public function upgradeServer(string $id, string $offerId) : array
    {
        return $this->client->patch(
            "vms/{$id}",
            [
                'offer_id' => $offerId
            ]
        );
    }

    public function getConsoleUrl(string $id) : array
    {
        return $this->client->get(
            "vms/{$id}/console"
        );
    }

    public function detachRescueIso(string $id) : array
    {
        return $this->client->post("vms/{$id}/detachRescue");
    }

    public function attachRescueIso(string $id) : array
    {
        return $this->client->post("vms/{$id}/attachRescue");
    }

    public function rebootServer(string $id) : array
    {
        return $this->client->post("vms/{$id}/start");
    }

    public function stopServer(string $id) : array
    {
        return $this->client->post("vms/{$id}/stop");
    }

    public function startServer(string $id) : array
    {
        return $this->client->post("vms/{$id}/start");
    }

    public function deleteServer(string $id) : array
    {
        return $this->client->delete("vms/{$id}");
    }

    public function showDetails(string $id) : array
    {
        return $this->client->get("vms/{$id}/details");
    }

    public function getUsage()
    {
        return $this->client->get("usage");
    }

    public function listOffers() : array
    {
        return $this->client->get("products/offers", [
            'filter.category' => 'compute_servers',
            'include'         => 'categories',
            'limit'           => 50,
            'page'            => 1
        ]);
    }

    public function listDatacenters() : array
    {
        return $this->client->get('datacenters');
    }

    public function listTemplates() : array
    {
        return $this->client->get('templates');
    }
}
