<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Tests;

use PHPUnit\Framework\TestCase;
use SandwaveIo\CloudSdkPhp\Support\UserDataFactory;

class UserDataFactoryTest extends TestCase
{
    public function test_generate_user_data()
    {
        $decodedResult = 'I2Nsb3VkLWNvbmZpZwptYW5hZ2VfZXRjX2hvc3RzOiB0cnVlCmZxZG46IGpvZWpvZQpob3N0bmFtZTogam9lam9lCnRpbWV6b25lOiBFdXJvcGUvQW1zdGVyZGFtCgpwYXNzd29yZDogaG9paG9pCnNzaF9wd2F1dGg6IFRydWUKCg==';
        $class = new UserDataFactory();
        // verander deze parameters niet want dan komt het sws niet overeen met decodedResult
        $result = $class->generateUserData('joejoe', 'hoihoi', []);
        $this->assertSame($result, $decodedResult);
    }

    public function test_generate_user_data_with_ssh_keys()
    {
        $decodedResult = 'I2Nsb3VkLWNvbmZpZwptYW5hZ2VfZXRjX2hvc3RzOiB0cnVlCmZxZG46IGpvZWpvZQpob3N0bmFtZTogam9lam9lCnRpbWV6b25lOiBFdXJvcGUvQW1zdGVyZGFtCgpwYXNzd29yZDogaG9paG9pCnNzaF9wd2F1dGg6IFRydWUKCgpzc2hfYXV0aG9yaXplZF9rZXlzOgogICAgLSBmZHNmZHNmZDM0NDNkZmdnZmQK';
        $class = new UserDataFactory();
        // verander deze parameters niet want dan komt het sws niet overeen met decodedResult
        $result = $class->generateUserData('joejoe', 'hoihoi', ['fdsfdsfd3443dfggfd']);
        $this->assertSame($result, $decodedResult);
    }
}
