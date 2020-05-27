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

        $this->assertSame($title, $specification->getTitle());
        $this->assertSame($value, $specification->getValue());
        $this->assertSame($unit, $specification->getUnit());
    }
}
