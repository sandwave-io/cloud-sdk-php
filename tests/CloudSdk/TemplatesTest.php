<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

class TemplatesTest extends AbstractCloudSdkCase
{
    public function test_list_templates()
    {
        $sdk = $this->getSdkWithMockedClient(
            200,
            'json/template_list.json',
            'get',
            'templates'
        );

        $json = $sdk->listTemplates();

        $this->assertTrue(is_array($json));
        $this->assertNotSame([], $json);
        $this->assertArrayContains('display_name', 'CentOS 8.1', $json);
    }
}
