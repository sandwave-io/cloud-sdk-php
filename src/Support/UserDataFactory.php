<?php declare(strict_types = 1);

namespace SandwaveIo\CloudSdkPhp\Support;

use Symfony\Component\Yaml\Yaml;

class UserDataFactory
{
    /**
     * @param string        $hostname
     * @param string        $password
     * @param array<string> $sshKeys
     *
     * @return string
     */
    public function generateUserData(string $hostname, string $password, array $sshKeys = []): string
    {
        $dump = Yaml::dump(
            [
                'manage_etc_hosts' => true,
                'fqdn' => $hostname,
                'hostname' => explode('.', $hostname)[0],
                'timezone' => 'Europe/Amsterdam',
                'password' => $password,
                'ssh_pwauth' => true,
            ]
        );

        // We cast the result of preg_replace, as it may return null. And preg_replace cannot take null as an input.
        $dump = (string) preg_replace('/True/', 'true', str_replace('true', 'True', $dump), 1);
        $dump = (string) preg_replace('/msterdam/', "msterdam\n", $dump, 1);
        $dump = (string) preg_replace('/True/', "True\n", $dump, 1);
        $dump = (string) preg_replace('/manage_etc_hosts/', "#cloud-config\nmanage_etc_hosts", $dump, 1);

        if (! empty($sshKeys)) {
            $dump .= "\n" . Yaml::dump([
                'ssh_authorized_keys' => $sshKeys,
            ]);
        }

        return base64_encode($dump);
    }
}
