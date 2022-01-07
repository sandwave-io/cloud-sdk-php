<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Client;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use SandwaveIo\CloudSdkPhp\Domain\AccountId;
use SandwaveIo\CloudSdkPhp\Exceptions\CloudHttpException;
use SandwaveIo\CloudSdkPhp\Exceptions\CloudNotFoundException;

final class APIClient
{
    const BASE_URL = 'https://api.pcextreme.nl/v2/compute/';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var AccountId
     */
    private $accountId;

    /**
     * @var Client
     */
    private $client;

    public function __construct(string $apiKey, AccountId $accountId, ?Client $client = null)
    {
        $this->apiKey = $apiKey;
        $this->accountId = $accountId;
        $this->client = $client ?? new Client([
            'base_uri' => self::BASE_URL,
        ]);
    }

    /**
     * @param string                   $endpoint
     * @param array<string,string|int> $queryParameters
     * @param int                      $expectedResponse
     *
     * @return array<mixed>
     */
    public function get(string $endpoint, array $queryParameters = [], int $expectedResponse = 200): array
    {
        $response = $this->client->get($endpoint . $this->queryParameters($queryParameters), [
            'headers' => $this->buildHeaders(),
            'http_errors' => false,
        ]);
        return $this->handleResponse($response, $expectedResponse);
    }

    /**
     * @param string                   $endpoint
     * @param array<string,string>     $body
     * @param array<string,string|int> $queryParameters
     * @param int                      $expectedResponse
     *
     * @return array<mixed>
     */
    public function post(
        string $endpoint,
        array $body = [],
        array $queryParameters = [],
        int $expectedResponse = 201
    ): array {
        $response = $this->client->post($endpoint . $this->queryParameters($queryParameters), [
            'headers' => $this->buildHeaders(),
            'http_errors' => false,
            'json' => $body,
        ]);
        return $this->handleResponse($response, $expectedResponse);
    }

    /**
     * @param string                   $endpoint
     * @param array<string,string>     $body
     * @param array<string,string|int> $queryParameters
     * @param int                      $expectedResponse
     *
     * @return array<mixed>
     */
    public function patch(
        string $endpoint,
        array $body = [],
        array $queryParameters = [],
        int $expectedResponse = 204
    ): array {
        $response = $this->client->patch($endpoint . $this->queryParameters($queryParameters), [
            'headers' => $this->buildHeaders(),
            'http_errors' => false,
            'json' => $body,
        ]);
        return $this->handleResponse($response, $expectedResponse);
    }

    /**
     * @param string                   $endpoint
     * @param array<string,string>     $body
     * @param array<string,string|int> $queryParameters
     * @param int                      $expectedResponse
     *
     * @return array<mixed>
     */
    public function put(
        string $endpoint,
        array $body = [],
        array $queryParameters = [],
        int $expectedResponse = 204
    ): array {
        $response = $this->client->put($endpoint . $this->queryParameters($queryParameters), [
            'headers' => $this->buildHeaders(),
            'http_errors' => false,
            'json' => $body,
        ]);
        return $this->handleResponse($response, $expectedResponse);
    }

    /**
     * @param string                   $endpoint
     * @param array<string,string|int> $queryParameters
     * @param int                      $expectedResponse
     *
     * @return array<mixed>
     */
    public function delete(string $endpoint, array $queryParameters = [], int $expectedResponse = 204): array
    {
        $response = $this->client->delete($endpoint . $this->queryParameters($queryParameters), [
            'headers' => $this->buildHeaders(),
            'http_errors' => false,
        ]);
        return $this->handleResponse($response, $expectedResponse);
    }

    /**
     * Check for status code and parse JSON.
     *
     * @param ResponseInterface $response
     * @param int               $expectedResponse
     *
     * @return array<mixed>
     */
    private function handleResponse(ResponseInterface $response, int $expectedResponse = 200): array
    {
        if ($response->getStatusCode() !== $expectedResponse) {
            $message = sprintf(
                "API responded with %s, expected %s. Response body:\n%s",
                $response->getStatusCode(),
                $expectedResponse,
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

        $responseText = (string) $response->getBody();
        $json = json_decode($responseText, true);

        if (json_last_error() !== 0) {
            throw new CloudHttpException("Could not parse JSON reponse body:\n" . $response->getBody());
        }

        return ($response->getStatusCode() === $expectedResponse && array_key_exists('data', $json)) ?
            $json['data'] :
            $json;
    }

    /**
     * Render header array for use in guzzle client.
     *
     * @return array<string,string>
     */
    private function buildHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];
    }

    /**
     * Includes account_id parameter.
     * Returns a string like so: "?account_id=xxx-yyy-zzz&foo=bar".
     *
     * @param array<string,string|int> $parameters
     *
     * @return string
     */
    private function queryParameters(array $parameters): string
    {
        return '?' .
            http_build_query(
                array_merge(
                    $parameters,
                    [
                        'account_id' => (string) $this->accountId,
                    ]
                )
            );
    }
}
