# pcextreme/cloud-sdk-php

PHP package to to deploy and configure virtual servers on the [PCextreme](https://pcextreme.com) infrastructure.

## Usage

```php
use PCextreme\CloudSdkPhp\CloudSdk;

$cloudSdk = new CloudSdk('your-pcextreme-api-key', 'your-pcextreme-account-uuid');

$cloudSdk->listServers(); // list all servers under your account
```

### Available methods

The endpoints that are currently implemented are not all existing endpoints. If you need an other endpoint to be
implemented, create an issue or open a PR. 🙂

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
    'id'                    => 'aaaa-bbbb-cccc-dddd-eeee'
    'display_name'          => 'display_name',
    'status'                => 'Running',
    'rescue_iso_attached'   => 0,
    'has_security_group'    => true,

    'created_at'            => '2020-04-02T09:50:13+00:00',
    'updated_at'            => '2020-04-02T09:50:13+00:00',
];
```

#### Show server

```php
$cloudSdk->showServer('aaaa-bbbb-cccc-dddd-eeee');
``` 

This will return an array having the requested server.


#### Show details server

```php
$cloudSdk->showDetails(
    'aaaa-bbbb-cccc-dddd-eeee'
);
``` 

This will return an array with the details of the requested server.

```php
[
    "id":"aaaa-bbbb-cccc-dddd-eeee",
    "name":"aaaa-bbbb-cccc-dddd-eeee",
    "displayname":"Name",
    "account":"3",
    "userid":"ad09ecc3-0356-4fva-ab42-d3dee2b08d82",
    "username":"3",
    "domainid":"6f243d98-7f2f-4276-9381-68e2a76f8b33",
    "domain":"3",
    "created":"2019-12-04T10:10:28+0100",
    "state":"Running",
    "haenable":false,
    "zoneid":"10c85e3a-b499-4b73-a78d-f2f48ca2a3ba",
    "zonename":"zone03.ams02.cldin.net",
    "templateid":"50ea3730-6cf4-47c2-9663-387d16fa8c72",
    "templatename":"Ubuntu 18.04.2",
    "templatedisplaytext":"Ubuntu 18.04.2 20GB",
    "passwordenabled":true,
    "serviceofferingid":"53a3b7ab-8f9f-4ee1-b289-db70a29168aa",
    "serviceofferingname":"pcx_standard_custom",
    "cpunumber":16,"cpuspeed":2000,"memory":65536,"cpuused":"0.11%",
    "networkkbsread":898116,"networkkbswrite":40097,"diskkbsread":672,
    "diskkbswrite":4583328,"memorykbs":67108864,"memoryintfreekbs":67005920,
    "memorytargetkbs":67108864,"diskioread":168,"diskiowrite":346239,
    "guestosid":"4ba6aae8-a4a1-11e9-814e-5254000588f7","rootdeviceid":0,
    "rootdevicetype":"ROOT","securitygroup":[{"id":"a259a05b-8ea8-4fb0-97fb-9735261de5cb",
    "name":"epic-vm","account":"3",
    "ingressrule":[],"egressrule":[],"tags":[],"virtualmachineids":[]}],
    "nic":[{"id":"b707be76-7f82-44d0-b900-9fbdaba591bc","networkid":"a0234ec3-bdb6-46b8-80a1-37144d7c3928",
    "networkname":"Guest Network 1","netmask":"255.255.255.0","gateway":"185.109.216.3","ipaddress":"185.109.216.118",
    "isolationuri":"vxlan:\/\/500","broadcasturi":"vxlan:\/\/500","traffictype":"Guest","type":"Shared","isdefault":true,
    "macaddress":"1e:00:1f:00:00:72","ip6gateway":"2a05:1500:600::1","ip6cidr":"2a05:1500:600::\/64","ip6address":"2a05:1500:600:0:1c00:1fff:fe00:72",
    "secondaryip":[],"extradhcpoption":[]}],"hypervisor":"KVM","details":{"cpuNumber":"16","memory":"65536","cpuSpeed":"2000","memoryOvercommitRatio":"1.0",
    "cpuOvercommitRatio":"4.0","rootdisksize":"1280","rootDiskController":"scsi"},"affinitygroup":[],"isdynamicallyscalable":true,
    "ostypeid":"4ba6aae8-a4a1-11e9-814e-5254000588f7","tags":[]
]
```

#### Upgrade server

```php
$cloudSdk->upgradeServer(
    'aaaa-bbbb-cccc-dddd-eeee',
    'pcexterme-offer-uuid' // check the listOffers method to get this id.
);
``` 

This will return true if succesful.

#### Detach rescue ISO server

```php
$cloudSdk->detachRescueIso(
    'aaaa-bbbb-cccc-dddd-eeee'
);
``` 

This will return true if succesful.

#### Attach rescue ISO server

```php
$cloudSdk->detachRescueIso(
    'aaaa-bbbb-cccc-dddd-eeee'
);
``` 

This will return true if succesful.

#### Reboot server

```php
$cloudSdk->rebootServer(
    'aaaa-bbbb-cccc-dddd-eeee'
);
```  

This will return true if succesful.

#### Start server

```php
$cloudSdk->startServer(
    'aaaa-bbbb-cccc-dddd-eeee'
);
``` 

This will return true if succesful.

#### Stop server

```php
$cloudSdk->stopServer(
    'aaaa-bbbb-cccc-dddd-eeee'
);
``` 

This will return true if succesful.


#### Delete server

```php
$cloudSdk->deleteServer(
    'aaaa-bbbb-cccc-dddd-eeee'
);
``` 

This will return true if succesfully deleted, note: this doesn't mean it will be completely expunged from cloudstack.


#### Get current RAM usage for account

```php
$cloudSdk->getUsage();
``` 

This will return a data array including the 'ram' for the requested account.


### Get offers for account
```php
$cloudSdk->listOffers();
```

```php
[
    'id'                 => 'aaaa-bbbb-cccc-dddd-eeee',
    'account_id'         => null,
    'billing_period'     => 12,
    'price'              => 9990,
    'type'               => 'usage',
    'sku'                => 'UNIQUE_OFFER',
    'name'               => 'server',
    'description'        => 'server',
    'show_in_store'      => 0,
    'specifications'     => [],

    'created_at'         => '2020-04-02T09:50:13+00:00',
    'updated_at'         => '2020-04-02T09:50:13+00:00',
];
```

### Get datacenters for account
```php
$cloudSdk->listDatacenters();
```

```php
[
    'id'            => 'aaaa-bbbb-cccc-dddd-eeee',
    'name'          => 'ams01'
    'description'   => 'AMSTERDAM'
    'city'          => 'ams',
    'country'       => 'nl',
    'timezone'      => 'Europe/Amsterdam',
    'standard_enabled' => true,
    'ha_enabled' => true
]
```

### Get templates for account
```php
$cloudSdk->listTemplates();
```

```php
[
    'id'            => 'aaaa-bbbb-cccc-dddd-eeee',
    'display_name'  => 'FEDORE 30'
    'os'            => 'fedora'
    'version'       => 30,
    'created_at'    => '2020-04-02T09:50:13+00:00',
    'updated_at'    => '2020-04-02T09:50:13+00:00'
];
```

### Get console url for current sever
```php
$cloudSdk->listTemplates();
```

```php
[
    'url' => 'www.test.nl'
];
```



## Contributing

### Unit testing

Run PHPUnit:

```
vendor/bin/phpunit
```

### PHPStan

You can run phpstan like so:

```
vendor/bin/phpstan analyze src --level=5
```
