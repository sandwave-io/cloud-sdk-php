<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Offer;

final class OfferTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function test_constructor(): void
    {
        $offer = Offer::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/offer.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );

        self::assertSame('ade61745-e399-401e-bdb4-b25487143e30', (string) $offer->getId());
        self::assertNull($offer->getAccountId());
        self::assertFalse($offer->isCustomOffering());
        self::assertSame(1, $offer->getBillingPeriodInMonths());
        self::assertSame(500, $offer->getPrice()->toCents());
        self::assertSame('usage', $offer->getType());
        self::assertSame('compute_standard_1gb', $offer->getSku());
        self::assertSame('Standard 1GB server', $offer->getName());
        self::assertSame('Standard 1GB server', $offer->getDescription());
        self::assertTrue($offer->isShowInStore());

        $specifications = $offer->getSpecifications();
        self::assertSame(7, $specifications->count());

        $firstSpecification = $specifications->current();
        self::assertSame('cpu', $firstSpecification?->getTitle());
        self::assertSame('1', $firstSpecification?->getValue());
        self::assertSame('amount', $firstSpecification?->getUnit());

        self::assertSame('2019-09-09T08:58:05+00:00', $offer->getCreatedAt()->format(DateTime::W3C));
        self::assertSame('2019-09-09T08:58:05+00:00', $offer->getUpdatedAt()->format(DateTime::W3C));
    }

    /**
     * @throws \JsonException
     */
    public function testConstructorThrowsExceptionForInvalidCreatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $network = Offer::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/offer_invalid_createdat.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );
    }

    /**
     * @throws \JsonException
     */
    public function testConstructorThrowsExceptionForInvalidUpdatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $network = Offer::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/offer_invalid_updatedat.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );
    }
}
