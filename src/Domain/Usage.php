<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Domain;

final class Usage
{
    /** @var int */
    private $ramInGbs;

    private function __construct(int $ramInGbs)
    {
        $this->ramInGbs = $ramInGbs;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): Usage
    {
        return new Usage(
            $data['ram']
        );
    }

    public function getRamInGbs(): int
    {
        return $this->ramInGbs;
    }
}
