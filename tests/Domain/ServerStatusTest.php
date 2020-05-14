<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\ServerStatus;

final class ServerStatusTest extends TestCase
{
    public function testCanConstruct(): void
    {
        $running = ServerStatus::running();
        $this->assertEquals($running, ServerStatus::fromString((string) $running));

        $stopping = ServerStatus::stopping();
        $this->assertEquals($stopping, ServerStatus::fromString((string) $stopping));

        $stopped = ServerStatus::stopped();
        $this->assertEquals($stopped, ServerStatus::fromString((string) $stopped));

        $starting = ServerStatus::starting();
        $this->assertEquals($starting, ServerStatus::fromString((string) $starting));

        $rebooting = ServerStatus::rebooting();
        $this->assertEquals($rebooting, ServerStatus::fromString((string) $rebooting));
    }

    public function testFromStringThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $bla = ServerStatus::fromString('nonexisting status!');
    }
}
