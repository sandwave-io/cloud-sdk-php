<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\ComputeSdk;

final class TemplateTest extends AbstractComputeSdkCase
{
    public function test_list_templates(): void
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/template_list.json',
            'get',
            'templates'
        );

        $templates = $sdk->listTemplates();

        self::assertSame(6, $templates->count());

        $displayNames = [];
        foreach ($templates as $template) {
            $displayNames[] = $template?->getDisplayName();
        }
        self::assertSame('CentOS 8.1', $displayNames[5]);
    }
}
