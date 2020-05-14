<?php declare(strict_types=1);

namespace SandwaveIo\CloudSdkPhp\Tests\CloudSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use SandwaveIo\CloudSdkPhp\Domain\TemplateCollection;
use SandwaveIo\CloudSdkPhp\Exceptions\CloudHttpException;
use SandwaveIo\CloudSdkPhp\CloudSdk;
use SandwaveIo\CloudSdkPhp\Client\APIClient;
use SandwaveIo\CloudSdkPhp\Support\UserDataFactory;

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

        $templates = $sdk->listTemplates();

        $this->assertInstanceOf(TemplateCollection::class, $templates);
        $this->assertEquals(6, $templates->count());

        $displayNames = [];
        foreach ($templates as $template) {
            $displayNames[] = $template->getDisplayName();
        }
        $this->assertEquals('CentOS 8.1', $displayNames[5]);
    }
}
