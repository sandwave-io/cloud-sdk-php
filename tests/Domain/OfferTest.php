<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
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
        $this->assertSame(null, $offer->getAccountId());
        $this->assertSame(false, $offer->isCustomOffering());
        $this->assertSame(1, $offer->getBillingPeriodInMonths());
        $this->assertSame(500, $offer->getPrice()->toCents());
        $this->assertSame('usage', $offer->getType());
        $this->assertSame('compute_standard_1gb', $offer->getSku());
        $this->assertSame('Standard 1GB server', $offer->getName());
        $this->assertSame('Standard 1GB server', $offer->getDescription());
        $this->assertSame(true, $offer->isShowInStore());

        $specifications = $offer->getSpecifications();
        $this->assertSame(7, $specifications->count());

        $firstSpecification = $specifications->current();
        $this->assertSame('cpu', $firstSpecification->getTitle());
        $this->assertSame('1', $firstSpecification->getValue());
        $this->assertSame('amount', $firstSpecification->getUnit());

        $this->assertSame('2019-09-09T08:58:05+00:00', $offer->getCreatedAt()->format(DateTime::W3C));
        $this->assertSame('2019-09-09T08:58:05+00:00', $offer->getUpdatedAt()->format(DateTime::W3C));
    }

    public function testConstructorThrowsExceptionForInvalidCreatedAt() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $network = Offer::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/offer_invalid_createdat.json'),
                true
            )
        );
    }

    public function testConstructorThrowsExceptionForInvalidUpdatedAt() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $network = Offer::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/offer_invalid_updatedat.json'),
                true
            )
        );
    }
}
