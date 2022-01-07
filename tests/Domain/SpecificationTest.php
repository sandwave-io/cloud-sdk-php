<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Specification;

final class SpecificationTest extends TestCase
{
    public function testCanConstruct(): void
    {
        $title = 'A Title';
        $value = 'A value';
        $unit = 'A unit';

        $specification = new Specification($title, $value, $unit);

        self::assertSame($title, $specification->getTitle());
        self::assertSame($value, $specification->getValue());
        self::assertSame($unit, $specification->getUnit());
    }
}
