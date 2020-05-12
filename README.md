# sandwave-io/cloud-sdk-php

PHP package to to deploy and configure virtual servers on the [PCextreme](https://pcextreme.com) infrastructure.

## Contributing

### PHP-CS-Fixer

Run PHP-CS-Fixer:

```
vendor/bin/php-cs-fixer fix
```

The config can be found in `.php_cs.dist`

### Unit testing

Run PHPUnit:

```
vendor/bin/phpunit
```

The config can be found in `phpunit.xml`

### PHPStan

You can run PHPStan like so:

```
vendor/bin/phpstan analyze src --level=8
```

The config can be found in `phpstan.neon`

## Usage

```php
use PCextreme\CloudSdkPhp\CloudSdk;

$cloudSdk = new CloudSdk('your-pcextreme-api-key', 'your-pcextreme-account-uuid');

$cloudSdk->listServers(); // list all servers under your account
```

### Available methods

The endpoints that are currently implemented are not all existing endpoints. If you need an other endpoint to be
implemented, create an issue or open a PR. 🙂

* [Deploy server](#deploy-server)
* [List servers](#list-servers)
* [Show server](#show-server)
* [Server details](#server-details)
* [Upgrade server](#upgrade-server)
* [Detach rescue ISO server](#detach-rescue-iso-server)
* [Attach rescue ISO server](#attach-rescue-iso-server)
* [Reboot server](#reboot-server)
* [Start server](#start-server)
* [Stop server](#stop-server)
* [Delete server](#delete-server)
* [Get current RAM usage for account](#get-current-ram-usage-for-account)

#### Deploy server

```php
$cloudSdk->deployServer(
    'example.com',
    'SuperSecretPassword',
    'pcexterme-offer-uuid', // check the listOffers method to get this id.
    'pcexterme-template-uuid', // check the listTemplates method to get this id.
    'pcexterme-datacenter-uuid' // check the listDatacenters method to get this id.
);
``` 

This will return the ID of the created server:
```php
[
    'id' => 'aaaa-bbbb-cccc-dddd-eeee'
]
```

#### List servers

```php
$cloudSdk->listServers();
``` 

This will return an array having all servers attached to your account.

```php
[
    [
        'id'                    => 'aaaa-bbbb-cccc-dddd-eeee',
        'display_name'          => 'display_name',
        'status'                => 'Running',
        'rescue_iso_attached'   => false,
        'has_security_group'    => true,
    
        'created_at'            => '2020-04-02T09:50:13+00:00',
        'updated_at'            => '2020-04-02T09:50:13+00:00',
    ],
];
```

#### Show server

You can also check out a specific server.
```php
$cloudSdk->showServer('aaaa-bbbb-cccc-dddd-eeee');
``` 

```php
[
    'id'                    => 'aaaa-bbbb-cccc-dddd-eeee',
    'display_name'          => 'display_name',
    'status'                => 'Running',
    'rescue_iso_attached'   => false,
    'has_security_group'    => true,

    'created_at'            => '2020-04-02T09:50:13+00:00',
    'updated_at'            => '2020-04-02T09:50:13+00:00',
];
```

#### Server details

You could use this call to fetch data from the underlying Cloudstack system.

```php
$cloudSdk->showDetails('aaaa-bbbb-cccc-dddd-eeee');
``` 

Check out [the cloudstack docs](https://cloudstack.apache.org/api/apidocs-4.12/apis/listVirtualMachines.html) to see all the available information.

```php
[
    "id"        => "aaaa-bbbb-cccc-dddd-eeee",
    "name"      => "aaaa-bbbb-cccc-dddd-eeee",
    "displayname" => "Name",
    "account" => "3",
    "userid" => "ad09ecc3-0356-4fva-ab42-d3dee2b08d82",
    "username" => "3",
    "domainid" => "6f243d98-7f2f-4276-9381-68e2a76f8b33",
    "domain" => "3",
    "created" => "2019-12-04T10:10:28+0100",
    "state" => "Running",
    "haenable" => false,
    "zoneid" => "10c85e3a-b499-4b73-a78d-f2f48ca2a3ba",
    "zonename" => "zone03.ams02.cldin.net",
    "templateid" => "50ea3730-6cf4-47c2-9663-387d16fa8c72",
    "templatename" => "Ubuntu 18.04.2",
    "templatedisplaytext" => "Ubuntu 18.04.2 20GB",
    "passwordenabled" => true,
    "serviceofferingid" => "53a3b7ab-8f9f-4ee1-b289-db70a29168aa",
    "serviceofferingname" => "pcx_standard_custom",
    "cpunumber" => 16,
    "cpuspeed" => 2000,
    "memory" => 65536,
    "cpuused" => "0.11%",
    "networkkbsread" => 898116,
    "networkkbswrite" => 40097,
    "diskkbsread" => 672,
    "diskkbswrite" => 4583328,
    "memorykbs" => 67108864,
    "memoryintfreekbs" => 67005920,
    "memorytargetkbs" => 67108864,
    "diskioread" => 168,
    "diskiowrite" => 346239,
    "guestosid" => "4ba6aae8-a4a1-11e9-814e-5254000588f7",
    "rootdeviceid" => 0,
    "rootdevicetype" => "ROOT",
    "securitygroup" => [
        [
            "id" => "a259a05b-8ea8-4fb0-97fb-9735261de5cb",
            "name" => "epic-vm","account" => "3",
            "ingressrule" => [],
            "egressrule" => [],
            "tags" => [],
            "virtualmachineids" => [],
        ],
    ],
    "nic" => [
        [
            "id" => "b707be76-7f82-44d0-b900-9fbdaba591bc",
            "networkid" => "a0234ec3-bdb6-46b8-80a1-37144d7c3928",
            "networkname" => "Guest Network 1",
            "netmask" => "255.255.255.0",
            "gateway" => "185.109.216.3",
            "ipaddress" => "185.109.216.118",
            "isolationuri" => "vxlan:\/\/500",
            "broadcasturi" => "vxlan:\/\/500",
            "traffictype" => "Guest",
            "type" => "Shared",
            "isdefault" => true,
            "macaddress" => "1e:00:1f:00:00:72",
            "ip6gateway" => "2a05:1500:600::1",
            "ip6cidr" => "2a05:1500:600::\/64",
            "ip6address" => "2a05:1500:600:0:1c00:1fff:fe00:72",
            "secondaryip" => [],
            "extradhcpoption" => [],
        ]
    ],
    "hypervisor" => "KVM",
    "details" => [
        "cpuNumber" => "16",
        "memory" => "65536",
        "cpuSpeed" => "2000",
        "memoryOvercommitRatio" => "1.0",
        "cpuOvercommitRatio" => "4.0",
        "rootdisksize" => "1280",
        "rootDiskController" => "scsi"
    ],
    "affinitygroup" => [],
    "isdynamicallyscalable" => true,
    "ostypeid" => "4ba6aae8-a4a1-11e9-814e-5254000588f7",
    "tags" => []
];
```

#### Upgrade server

In the case that you want to upgrade an existing server, you can use upgradeServer.

Note that the VM must be stopped when you run this.

```php
$cloudSdk->upgradeServer(
    'aaaa-bbbb-cccc-dddd-eeee',
    'pcexterme-offer-uuid' // check the listOffers method to get this id.
);
``` 

#### Detach rescue ISO server

Rescue ISO's can be used to salvage a broken server.

```php
$cloudSdk->detachRescueIso('aaaa-bbbb-cccc-dddd-eeee');
``` 

#### Attach rescue ISO server

Rescue ISO's can be used to salvage a broken server.

```php
$cloudSdk->detachRescueIso('aaaa-bbbb-cccc-dddd-eeee');
``` 

#### Reboot server

```php
$cloudSdk->rebootServer('aaaa-bbbb-cccc-dddd-eeee');
```  

#### Start server

```php
$cloudSdk->startServer('aaaa-bbbb-cccc-dddd-eeee');
``` 

#### Stop server

```php
$cloudSdk->stopServer('aaaa-bbbb-cccc-dddd-eeee');
``` 

#### Delete server

```php
$cloudSdk->deleteServer('aaaa-bbbb-cccc-dddd-eeee');
``` 

Note: there is a grace period within Cloudstack. So the data will not be immediately lost.


#### Get current RAM usage for account

Accounts are limited on resources by default. You can retrieve your current resource usage using this method.

If you wish to broaden your limits, contact PCextreme. 

```php
$cloudSdk->getUsage();
``` 

```php
[
    'ram' => 1 // in GB's
];
```

#### Get offers for account

Before you deploy or upgrade a server, you must retrieve the offer ID that you want to use.

```php
$cloudSdk->listOffers();
```

```php
[
    [
        'id'                 => 'aaaa-bbbb-cccc-dddd-eeee',
        'account_id'         => null, // Contains your account ID in the case of a custom offering.
        'billing_period'     => 12, // Billing period in months
        'price'              => 9990, // Price in cents.
        'type'               => 'usage',
        'sku'                => 'compute_standard_2gb',
        'name'               => 'server',
        'description'        => 'server',
        'show_in_store'      => 0,
        'specifications'     => [
            // Filled with specifications depending on the offering.
        ],
    
        'created_at'         => '2020-04-02T09:50:13+00:00',
        'updated_at'         => '2020-04-02T09:50:13+00:00',
    ]
];
```

#### Get datacenters for account

For a deployment, you also need to know what datacenter to deploy in.

```php
$cloudSdk->listDatacenters();
```

The response also contains the availability of standard and HA VM's. These are two different types of storage, 
the difference between the two VM's can also be identified based on the offer data.

```php
[
    [
        'id'            => 'aaaa-bbbb-cccc-dddd-eeee',
        'name'          => 'ams01',
        'description'   => 'Amsterdam',
        'city'          => 'ams',
        'country'       => 'nl',
        'timezone'      => 'Europe/Amsterdam',
        'standard_enabled'  => true,
        'ha_enabled'        => true
    ]
];
```

#### Get templates for account

The templates used for VM deployments are predefined and can be seen using listTemplates.

```php
$cloudSdk->listTemplates();
```

```php
[
    [
        'id'            => 'aaaa-bbbb-cccc-dddd-eeee',
        'display_name'  => 'Fedora 30',
        'os'            => 'fedora',
        'version'       => 30,
        'created_at'    => '2020-04-02T09:50:13+00:00',
        'updated_at'    => '2020-04-02T09:50:13+00:00'
    ]
];
```

#### Get private networks for account

The templates used for VM deployments are predefined and can be seen using listTemplates.

```php
$cloudSdk->listNetworks();
```

```php
[
    [
        "id" => "d11c06f5-ff1c-41cd-8fc9-1ebcb3084501",
        "display_name" => "An example (No SG)",
        "datacenter_id" => "36616598-8e93-4118-a03c-94f99e5e1169",
        "manager" => "man.zone03.ams02.cldin.net",
        "cidr_ipv4" => "185.109.217.64/26",
        "cidr_ipv6" => "2a05:1500:600:4::/64",
        "created_at" => "2019-09-09T08:58:05+00:00",
        "updated_at" => "2019-09-09T08:58:05+00:00"
    ]
];
```

#### Get console url for current sever

If you wish to open a browser based console, you can use this method to generate a URL.

```php
$cloudSdk->getConsoleUrl();
```

```php
[
    'url' => 'www.test.nl'
];
```
