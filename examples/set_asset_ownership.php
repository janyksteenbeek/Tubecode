<?php

DEFINE("OAUTH2_SERVICE_ACCOUNT_NAME", 	'YOUR GOOGLE SERVICE ACCOUNT');
DEFINE("OAUTH2_KEY_FILE", 				__DIR__.'/storage/key.p12');
DEFINE("CONTENT_OWNER",					'YOUR CONTENT OWNER ID');


//Prepare the Google Client
$client = new Google_Client();
Pascal\YouTubeApiHelper::authorize_service_account($client,OAUTH2_SERVICE_ACCOUNT_NAME,OAUTH2_KEY_FILE);
$partner = new Google_Service_YouTubePartner($client);

$asset_id = '';

$asset_ownership_territorries = [];
$asset_ownership_type='exclude';

Pascal\YouTubeApiHelper::set_asset_ownership($partner,$asset_id,$asset_ownership_type,$asset_ownership_territorries,new \Pascal\ContentOwner(CONTENT_OWNER)) ;

echo "Done updating Asset {$asset_id}";