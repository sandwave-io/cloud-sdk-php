<?php
declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Domain\Compute;

use SandwaveIo\CloudSdkPhp\Domain\AbstractCollection;

final class TemplateCollection extends AbstractCollection
{
    protected function __construct(Template ...$items)
    {
        parent::__construct(...$items);
    }

    public function current(): ?Template
    {
        return $this->items->current();
    }

    public static function fromArray(array $data): TemplateCollection
    {
        $templates = [];
        foreach ($data as $item) {
            $templates[] = Template::fromArray($item);
        }

        return new TemplateCollection(...$templates);
    }
}
