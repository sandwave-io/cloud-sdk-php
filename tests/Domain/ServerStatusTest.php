<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\ServerStatus;

final class ServerStatusTest extends TestCase
{
    public function testCanConstruct() : void
    {
        $running = ServerStatus::running();
        $this->assertTrue(ServerStatus::fromString((string) $running)->equals($running));

        $stopping = ServerStatus::stopping();
        $this->assertTrue(ServerStatus::fromString((string) $stopping)->equals($stopping));

        $stopped = ServerStatus::stopped();
        $this->assertTrue(ServerStatus::fromString((string) $stopped)->equals($stopped));

        $starting = ServerStatus::starting();
        $this->assertTrue(ServerStatus::fromString((string) $starting)->equals($starting));

        $rebooting = ServerStatus::rebooting();
        $this->assertTrue(ServerStatus::fromString((string) $rebooting)->equals($rebooting));
    }

    public function testFromStringThrowsException() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $bla = ServerStatus::fromString('nonexisting status!');
    }
}