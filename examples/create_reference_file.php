<?php

DEFINE("OAUTH2_SERVICE_ACCOUNT_NAME", 	'YOUR GOOGLE SERVICE ACCOUNT');
DEFINE("OAUTH2_KEY_FILE", 				__DIR__.'/storage/key.p12');
DEFINE("CONTENT_OWNER",					'YOUR CONTENT OWNER ID');


//Prepare the Google Client
$client = new Google_Client();
Pascal\YouTubeApiHelper::authorize_service_account($client,OAUTH2_SERVICE_ACCOUNT_NAME,OAUTH2_KEY_FILE);
$partner = new Google_Service_YouTubePartner($client);

$asset_id = '';
$asset_match_policy_id = '';
$claim_id = '';
$video_id = '';

if(Pascal\YouTubeApiHelper::set_match_policy($partner,$asset_id,$asset_match_policy_id,new \Pascal\ContentOwner(CONTENT_OWNER)) < 0) {
    echo "Error setting match policy";
} else {
    Pascal\YouTubeApiHelper::create_reference_file($partner,$youtube,$claim_id,$video_id,new \Pascal\ContentOwner(CONTENT_OWNER));
}