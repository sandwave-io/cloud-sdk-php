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
        self::assertTrue(ServerStatus::fromString((string) $deleting)->equals($deleting));

        $deleted = ServerStatus::deleted();
        self::assertTrue(ServerStatus::fromString((string) $deleted)->equals($deleted));

        $deploying = ServerStatus::deploying();
        self::assertTrue(ServerStatus::fromString((string) $deploying)->equals($deploying));

        $deployed = ServerStatus::deployed();
        self::assertTrue(ServerStatus::fromString((string) $deployed)->equals($deployed));

        $error = ServerStatus::error();
        self::assertTrue(ServerStatus::fromString((string) $error)->equals($error));

        $running = ServerStatus::running();
        self::assertTrue(ServerStatus::fromString((string) $running)->equals($running));

        $stopping = ServerStatus::stopping();
        self::assertTrue(ServerStatus::fromString((string) $stopping)->equals($stopping));

        $stopped = ServerStatus::stopped();
        self::assertTrue(ServerStatus::fromString((string) $stopped)->equals($stopped));

        $starting = ServerStatus::starting();
        self::assertTrue(ServerStatus::fromString((string) $starting)->equals($starting));

        $rebooting = ServerStatus::rebooting();
        self::assertTrue(ServerStatus::fromString((string) $rebooting)->equals($rebooting));

        $destroyed = ServerStatus::destroyed();
        self::assertTrue(ServerStatus::fromString((string) $destroyed)->equals($destroyed));
    }

    public function testFromStringThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $bla = ServerStatus::fromString('nonexisting status!');
    }
}
