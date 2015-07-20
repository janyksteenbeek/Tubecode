<?php

require_once __DIR__ . "/../vendor/autoload.php";

$service_account = file_get_contents(__DIR__ . '/service_account.json');

$contentOwner = \Tubecode\Factory::create($service_account, [], "YOUR CONTENT OWNER ID");

echo "Count: " . $contentOwner->partneredChannels()->count() . PHP_EOL;

foreach($contentOwner->partneredChannels()->all() as $channel)
{
    echo "ID: {$channel->getId()}" . PHP_EOL;
}