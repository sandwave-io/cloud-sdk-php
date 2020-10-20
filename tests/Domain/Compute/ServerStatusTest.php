<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\Domain\Compute;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Domain\Compute\ServerStatus;

final class ServerStatusTest extends TestCase
{
    public function testCanConstruct(): void
    {
        $deleting = ServerStatus::deleting();
        $this->assertTrue(ServerStatus::fromString((string) $deleting)->equals($deleting));

        $deleted = ServerStatus::deleted();
        $this->assertTrue(ServerStatus::fromString((string) $deleted)->equals($deleted));

        $deploying = ServerStatus::deploying();
        $this->assertTrue(ServerStatus::fromString((string) $deploying)->equals($deploying));

        $deployed = ServerStatus::deployed();
        $this->assertTrue(ServerStatus::fromString((string) $deployed)->equals($deployed));

        $error = ServerStatus::error();
        $this->assertTrue(ServerStatus::fromString((string) $error)->equals($error));

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

        $destroyed = ServerStatus::destroyed();
        $this->assertTrue(ServerStatus::fromString((string) $destroyed)->equals($destroyed));
    }

    public function testFromStringThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $bla = ServerStatus::fromString('nonexisting status!');
    }
}
