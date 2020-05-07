<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use SandwaveIo\CloudSdkPhp\CloudSdk;
use SandwaveIo\CloudSdkPhp\Domain\AccountId;

$sdk = new CloudSdk(
    '5FQuLEaTqMQuYlSPReOiYAO44aV1pQdx',
    AccountId::fromString('994b3b60-faa9-46c3-9f4d-568cca31cf52')
);

$servers = $sdk->listServers();
foreach ($servers as $server) {
    echo $server->getDisplayName() . "\t" . $server->getStatus() . "\t" . $server->getId() .  "\n";
//    var_dump($server);
}

//$dcs = $sdk->listDatacenters();
//foreach ($dcs as $dataCenter) {
//    echo $dataCenter->getName() . "\n";
////    var_dump($dataCenter);
//}

//$offers = $sdk->listOffers();
//foreach ($offers as $offer) {
//    echo $offer->getName() . "\n";
//}

//$templates = $sdk->listTemplates();
//foreach ($templates as $template) {
//    echo $template->getDisplayName() . "\n"; //time format error
//}

//$diskOffers = $sdk->listDiskOffers();
//foreach ($diskOffers as $diskOffer) {
//    var_dump($diskOffer);
//}

//$serverOffers = $sdk->listServerOffers();
//foreach ($serverOffers as $serverOffer) {
//    var_dump($serverOffer);
//}

var_dump($sdk->getConsoleUrl(\SandwaveIo\CloudSdkPhp\Domain\ServerId::fromString('d0e321e9-c995-4649-a601-43f086aa69bc')));