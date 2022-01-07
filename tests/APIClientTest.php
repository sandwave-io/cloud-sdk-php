<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use SandwaveIo\CloudSdkPhp\Client\APIClient;
use SandwaveIo\CloudSdkPhp\Domain\AccountId;
use SandwaveIo\CloudSdkPhp\Exceptions\CloudHttpException;
use SandwaveIo\CloudSdkPhp\Exceptions\CloudNotFoundException;

class APIClientTest extends TestCase
{
    public function test_client_constructor(): void
    {
        $client = new APIClient('this-is-my-api-key', AccountId::fromString(Uuid::NIL));
        self::assertNotNull($client);
    }

    public function test_get_request_success(): void
    {
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(200, [], '{"data": {"foo": "bar"}}'),
        ]));
        $client = new APIClient('this-is-my-api-key', AccountId::fromString(Uuid::NIL), new Client(['handler' => $handlerStack]));

        $response = $client->get('/test');
        self::assertSame(['foo' => 'bar'], $response);
    }

    public function test_get_request_404(): void
    {
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(404, [], '{"data": null}'),
        ]));
        $client = new APIClient('this-is-my-api-key', AccountId::fromString(Uuid::NIL), new Client(['handler' => $handlerStack]));

        $this->expectException(CloudNotFoundException::class);
        $client->get('/test');
    }

    public function test_get_request_bad_json(): void
    {
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(200, [], '{"data": dit is geen valide json 123 😂}'),
        ]));
        $client = new APIClient('this-is-my-api-key', AccountId::fromString(Uuid::NIL), new Client(['handler' => $handlerStack]));

        $this->expectException(CloudHttpException::class);
        $client->get('/test');
    }

    public function test_get_request_empty_body(): void
    {
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(200, ['Content-Length' => 0], ''),
        ]));
        $client = new APIClient('this-is-my-api-key', AccountId::fromString(Uuid::NIL), new Client(['handler' => $handlerStack]));

        $response = $client->get('/test');
        self::assertSame([], $response);
    }

    public function test_post_request_success(): void
    {
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(201, [], '{"data": {"foo": "bar"}}'),
        ]));
        $client = new APIClient('this-is-my-api-key', AccountId::fromString(Uuid::NIL), new Client(['handler' => $handlerStack]));

        $response = $client->post('/test');
        self::assertSame(['foo' => 'bar'], $response);
    }

    public function test_put_request_success(): void
    {
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(204, [], '{"data": {"foo": "bar"}}'),
        ]));
        $client = new APIClient('this-is-my-api-key', AccountId::fromString(Uuid::NIL), new Client(['handler' => $handlerStack]));

        $response = $client->put('/test');
        self::assertSame(['foo' => 'bar'], $response);
    }

    public function test_delete_request_success(): void
    {
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(204, [], '{"data": {"foo": "bar"}}'),
        ]));
        $client = new APIClient('this-is-my-api-key', AccountId::fromString(Uuid::NIL), new Client(['handler' => $handlerStack]));

        $response = $client->delete('/test');
        self::assertSame(['foo' => 'bar'], $response);
    }

    public function test_patch_request_success(): void
    {
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(204, [], '{"data": {"foo": "bar"}}'),
        ]));
        $client = new APIClient('this-is-my-api-key', AccountId::fromString(Uuid::NIL), new Client(['handler' => $handlerStack]));

        $response = $client->patch('/test');
        self::assertSame(['foo' => 'bar'], $response);
    }
}
