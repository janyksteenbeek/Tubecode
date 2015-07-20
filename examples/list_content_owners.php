<?php

require_once __DIR__ . "/../vendor/autoload.php";

$service_account = file_get_contents(__DIR__ . '/service_account.json');

$contentOwners = \Tubecode\Factory::create($service_account);

foreach($contentOwners->all() as $contentOwner) {
    echo "Content owner {$contentOwner->getDisplayName()} ({$contentOwner->getId()})\n";
}