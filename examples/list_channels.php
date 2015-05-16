<?php

DEFINE("OAUTH2_SERVICE_ACCOUNT_NAME", 	'YOUR GOOGLE SERVICE ACCOUNT');
DEFINE("OAUTH2_KEY_FILE", 				__DIR__.'/storage/key.p12');
DEFINE("CONTENT_OWNER",					'YOUR CONTENT OWNER ID');


//Prepare the Google Client
$client = new Google_Client();
Pascal\YouTubeApiHelper::authorize_service_account($client,OAUTH2_SERVICE_ACCOUNT_NAME,OAUTH2_KEY_FILE);
$youtube = new Google_Service_YouTube($client);
$partner = new Google_Service_YouTubePartner($client);

foreach(Pascal\YouTubeApiHelper::list_channels($youtube, new \Pascal\ContentOwner(CONTENT_OWNER), 'snippet') as $channel) {
    echo "Channel id ".$channel['id']." : ".$channel['snippet']['title']."\n\r <br>";
}