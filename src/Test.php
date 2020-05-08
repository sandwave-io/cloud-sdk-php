<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use SandwaveIo\CloudSdkPhp\CloudSdk;
use SandwaveIo\CloudSdkPhp\Domain\AccountId;
use SandwaveIo\CloudSdkPhp\Domain\DatacenterId;
use SandwaveIo\CloudSdkPhp\Domain\NetworkId;
use SandwaveIo\CloudSdkPhp\Domain\OfferId;
use SandwaveIo\CloudSdkPhp\Domain\ServerId;
use SandwaveIo\CloudSdkPhp\Domain\TemplateId;

$sdk = new CloudSdk(
    '5FQuLEaTqMQuYlSPReOiYAO44aV1pQdx',
    AccountId::fromString('994b3b60-faa9-46c3-9f4d-568cca31cf52')
);

//$servers = $sdk->listServers(10000);
//foreach ($servers as $server) {
//    echo $server->getDisplayName() . "\t" . $server->getStatus() . "\t" . $server->getId() .  "\n";
////    var_dump($server);
//}

//$dcs = $sdk->listDatacenters();
//foreach ($dcs as $dataCenter) {
//    echo $dataCenter->getName() . "\t" . $dataCenter->getId() . "\n";
////    var_dump($dataCenter);
//}
//echo "\n";

//$offers = $sdk->listOffers();
//foreach ($offers as $offer) {
//    echo $offer->getName() . "\n";
//}

//$diskOffers = $sdk->listDiskOffers();
//foreach ($diskOffers as $diskOffer) {
//    var_dump($diskOffer);
//}

//$serverOffers = $sdk->listServerOffers();
//foreach ($serverOffers as $serverOffer) {
//    echo $serverOffer->getName() . "- " . $serverOffer->getId() . "\n";
//}

//var_dump($sdk->getConsoleUrl(\SandwaveIo\CloudSdkPhp\Domain\ServerId::fromString('d0e321e9-c995-4649-a601-43f086aa69bc')));

$templates = $sdk->listTemplates();
foreach ($templates as $template) {
    echo $template->getDisplayName() . "\t". $template->getId() . "\n";
}

//var_dump($sdk->listNetworks());

//$serverId = $sdk->createServer(
//    'templatetest.sohosted.org',
//    'test123',
//    OfferId::fromString('c7d4l0l6-9l3b-4dce-a55a-04121bca5ha7'),
////    TemplateId::fromString('9a72cfcf-fd6a-4643-aa9d-a5d4cb081645'),
//    TemplateId::fromString('96e53149-5cb4-4189-b0ae-47e416f9e50b'),
//    DatacenterId::fromString('36616598-8e93-4118-a03c-94f99e5e1169'),
//    NetworkId::fromString('4f4e2198-5d45-11ea-83a9-06085a000090'),
//    []
//);

//    DatacenterId::fromString('5427178b-fc9b-4561-8a8b-fb45353fdb37'),

//var_dump($serverId);
//
//$servers = $sdk->listServers(10000);
//foreach ($servers as $server) {
//    echo $server->getDisplayName() . "\t" . $server->getStatus() . "\t" . $server->getId() .  "\n";
////    var_dump($server);
//}

//$serverId = ServerId::fromString('07bc65c6-0c6a-42c2-8e27-bdc6747f2c6a');
//var_dump($sdk->getConsoleUrl($serverId));
//var_dump($sdk->showServer($serverId));

//$sdk->rebootServer($serverId);

//$sdk->stopServer($serverId);

//$sdk->upgradeServer($serverId, OfferId::fromString('346fd2b8-339a-479f-8fcf-c30273db7vcd'));

//$sdk->startServer($serverId);
//var_dump($sdk->getConsoleUrl($serverId));

//$sdk->showServer($serverId);
//$sdk->stopServer($serverId);

//$sdk->deleteServer($serverId);