<?php declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Psr\Http\Message\RequestInterface;
use SandwaveIo\CloudSdkPhp\CloudSdk;
use SandwaveIo\CloudSdkPhp\Client\APIClient;
use SandwaveIo\CloudSdkPhp\Support\UserDataFactory;
use PHPUnit\Framework\TestCase;

class AbstractCloudSdkCase extends TestCase
{
    protected function getSdkWithMockedClient(int $responseCode, ?string $responsePath, string $assertMethod, string $assertPath, string $assertQuery = 'account_id=this-is-my-account-id') : CloudSdk
    {
        $response = ($responsePath) ? file_get_contents(__DIR__.'/'.$responsePath) : '';
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response($responseCode, [], $response)
        ]));
        $handlerStack->push(function (callable $handler) use ($assertMethod, $assertPath, $assertQuery) {
            return function (RequestInterface $request, array $options) use ($handler, $assertMethod, $assertPath, $assertQuery) {

                // Make some assertions
                $this->assertEquals($assertPath, $request->getUri()->getPath());
                $this->assertEquals($assertQuery, $request->getUri()->getQuery());
                $this->assertEquals(strtolower($assertMethod), strtolower($request->getMethod()));

                // Go on with business.
                return $handler($request, $options);
            };
        });
        $client = new APIClient('this-is-my-api-key', 'this-is-my-account-id', new Client(['handler' => $handlerStack]));
        return new CloudSdk('a', 'b', new UserDataFactory, $client);
    }

    protected function assertArrayContains(string $expectedKey, $expectedValue, array $array, string $message = 'Failed asserting that array contains value.') : void
    {
        $found = false;
        array_walk_recursive($array, function ($value, $key) use (&$found, $expectedKey, $expectedValue) {
            if ($key === $expectedKey && $value === $expectedValue) {
                $found = true;
                return;
            }
        });

        $this->assertTrue($found, $message);
    }
}
