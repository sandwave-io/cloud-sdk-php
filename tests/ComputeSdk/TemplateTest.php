<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\ComputeSdk;

use SandwaveIo\CloudSdkPhp\Domain\Compute\TemplateCollection;

class TemplateTest extends AbstractComputeSdkCase
{
    public function test_list_templates()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/template_list.json',
            'get',
            'templates'
        );

        $templates = $sdk->listTemplates();

        $this->assertInstanceOf(TemplateCollection::class, $templates);
        $this->assertSame(6, $templates->count());

        $displayNames = [];
        foreach ($templates as $template) {
            $displayNames[] = $template->getDisplayName();
        }
        $this->assertSame('CentOS 8.1', $displayNames[5]);
    }
}
