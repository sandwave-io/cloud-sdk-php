<?php

namespace SandwaveIo\CloudSdkPhp\Client;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use SandwaveIo\CloudSdkPhp\Exceptions\CloudHttpException;
use SandwaveIo\CloudSdkPhp\Exceptions\CloudNotFoundException;

final class APIClient
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $accountId;

    /**
     * @var Client
     */
    private $client;

    public function __construct(string $apiKey, string $accountId, ?Client $client = null)
    {
        $this->apiKey = $apiKey;
        $this->accountId = $accountId;
        $this->client = $client ?? new Client([
            'base_uri' => 'https://api.pcextreme.nl/v2/'
        ]);
    }

    public function get(string $endpoint, array $queryParameters = [], int $expectedResponse = 200)
    {
        $response = $this->client->get($endpoint . $this->queryParameters($queryParameters), [
            'headers' => $this->buildHeaders(),
        ]);
        return $this->handleResponse($response, $expectedResponse);
    }

    public function post(string $endpoint, array $body = [], array $queryParameters = [], int $expectedResponse = 201)
    {
        $response = $this->client->post($endpoint . $this->queryParameters($queryParameters), [
            'headers' => $this->buildHeaders(),
            'json' => $body,
        ]);
        return $this->handleResponse($response, $expectedResponse);
    }

    public function patch(string $endpoint, array $body = [], array $queryParameters = [], int $expectedResponse = 204)
    {
        $response = $this->client->patch($endpoint . $this->queryParameters($queryParameters), [
            'headers' => $this->buildHeaders(),
            'json' => $body,
        ]);
        return $this->handleResponse($response, $expectedResponse);
    }

    public function put(string $endpoint, array $body = [], array $queryParameters = [], int $expectedResponse = 204)
    {
        $response = $this->client->put($endpoint . $this->queryParameters($queryParameters), [
            'headers' => $this->buildHeaders(),
            'json' => $body,
        ]);
        return $this->handleResponse($response, $expectedResponse);
    }

    public function delete(string $endpoint, array $queryParameters = [], int $expectedResponse = 204)
    {
        $response = $this->client->delete($endpoint . $this->queryParameters($queryParameters), [
            'headers' => $this->buildHeaders(),
        ]);
        return $this->handleResponse($response, $expectedResponse);
    }

    /**
     * Check for status code and parse JSON.
     */
    private function handleResponse(ResponseInterface $response, int $expectedResponse = 200): array
    {
        if ($response->getStatusCode() !== $expectedResponse) {
            $message = sprintf(
                "API responded with %s:\n%s",
                $response->getStatusCode(),
                $response->getBody()
            );

            switch ($response->getStatusCode()) {
                case 404:
                    throw new CloudNotFoundException($message);
                default:
                    throw new CloudHttpException($message);
            }
        }
        if ($response->getBody()->getSize() === 0) {
            return [];
        }

        $json = json_decode($response->getBody(), true);

        if (json_last_error()) {
            throw new CloudHttpException("Could not parse JSON reponse body:\n" . $response->getBody());
        }

        return $response->getStatusCode() === 200 ? $json['data'] : $json;
    }

    /**
     * Render header array for use in guzzle client.
     */
    private function buildHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];
    }

    /**
     * Includes account_id parameter.
     * Returns a string like so: "?account_id=xxx-yyy-zzz&foo=bar"
     */
    private function queryParameters(array $parameters): string
    {
        return "?" . http_build_query(
                array_merge($parameters, ['account_id' => $this->accountId])
            );
    }
}
