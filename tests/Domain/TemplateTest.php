<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Template;

final class TemplateTest extends TestCase
{
    public function testCanConstruct() : void
    {
        $template = Template::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/template.json'),
                true
            )
        );

        $this->assertSame('d8f6397b-8d65-41fd-9db5-e4c1c7b6807b', (string) $template->getId());
        $this->assertSame('OpenBSD 6.6', $template->getDisplayName());
        $this->assertSame('OpenBSD', $template->getOperatingSystem());
        $this->assertSame('66', $template->getVersion());
        $this->assertSame('2020-04-02T09:50:13+00:00', $template->getCreatedAt()->format(DateTime::W3C));
        $this->assertSame('2020-04-02T09:50:13+00:00', $template->getUpdatedAt()->format(DateTime::W3C));
    }

    public function testConstructorThrowsExceptionForInvalidCreatedAt() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $network = Template::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/template_invalid_createdat.json'),
                true
            )
        );
    }

    public function testConstructorThrowsExceptionForInvalidUpdatedAt() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $network = Template::fromArray(
            json_decode(
                file_get_contents('tests/Domain/json/template_invalid_updatedat.json'),
                true
            )
        );
    }
}
