<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Network;
use SandwaveIo\CloudSdkPhp\Domain\Offer;

final class OfferTest extends TestCase
{
    public function test_constructor() : void
    {
        $offer = Offer::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/offer.json'),
                true
            )
        );

        $this->assertSame('ade61745-e399-401e-bdb4-b25487143e30', (string) $offer->getId());
    }

    public function testConstructorThrowsExceptionForInvalidCreatedAt() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $network = Network::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/offer_invalid_createdat.json'),
                true
            )
        );
    }

    public function testConstructorThrowsExceptionForInvalidUpdatedAt() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $network = Network::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/offer_invalid_updatedat.json'),
                true
            )
        );
    }
}
