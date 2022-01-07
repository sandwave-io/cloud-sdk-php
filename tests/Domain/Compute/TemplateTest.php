<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain\Compute;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Compute\Template;

final class TemplateTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function testCanConstruct(): void
    {
        $template = Template::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/template.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );

        self::assertSame('d8f6397b-8d65-41fd-9db5-e4c1c7b6807b', (string) $template->getId());
        self::assertSame('OpenBSD 6.6', $template->getDisplayName());
        self::assertSame('OpenBSD', $template->getOperatingSystem());
        self::assertSame('66', $template->getVersion());
        self::assertSame('2020-04-02T09:50:13+00:00', $template->getCreatedAt()->format(DateTime::W3C));
        self::assertSame('2020-04-02T09:50:13+00:00', $template->getUpdatedAt()->format(DateTime::W3C));
    }

    /**
     * @throws \JsonException
     */
    public function testConstructorThrowsExceptionForInvalidCreatedAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $network = Template::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/template_invalid_createdat.json'),
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
        $network = Template::fromArray(
            json_decode(
                (string) file_get_contents('tests/Domain/json/template_invalid_updatedat.json'),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );
    }
}
