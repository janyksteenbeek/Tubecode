<?php

DEFINE("OAUTH2_SERVICE_ACCOUNT_NAME", 	'YOUR GOOGLE SERVICE ACCOUNT');
DEFINE("OAUTH2_KEY_FILE", 				__DIR__.'/storage/key.p12');
DEFINE("CONTENT_OWNER",					'YOUR CONTENT OWNER ID');


//Prepare the Google Client
$client = new Google_Client();
Pascal\YouTubeApiHelper::authorize_service_account($client,OAUTH2_SERVICE_ACCOUNT_NAME,OAUTH2_KEY_FILE);
$partner = new Google_Service_YouTubePartner($client);

$asset_title = "My first Asset created via API";

$asset_id = Pascal\YouTubeApiHelper::create_asset($partner,$asset_title, new \Pascal\ContentOwner(CONTENT_OWNER));
echo "Created asset {$asset_id}\n";