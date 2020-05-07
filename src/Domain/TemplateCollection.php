<?php
declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Domain;

final class TemplateCollection extends AbstractCollection
{
    public function __construct(Template ...$items)
    {
        parent::__construct(...$items);
    }

    public function current(): ?Template
    {
        return $this->items->current();
    }

    /** @param array<array> $data */
    public static function fromArray(array $data): TemplateCollection
    {
        $templates = [];
        foreach ($data as $item) {
            $templates[] = Template::fromArray($item);
        }

        return new TemplateCollection(...$templates);
    }
}
