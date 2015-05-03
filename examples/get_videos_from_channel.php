<?php

DEFINE("OAUTH2_SERVICE_ACCOUNT_NAME", 	'YOUR GOOGLE SERVICE ACCOUNT');
DEFINE("OAUTH2_KEY_FILE", 				__DIR__.'/storage/key.p12');
DEFINE("CONTENT_OWNER",					'YOUR CONTENT OWNER ID');


//Prepare the Google Client
$client = new Google_Client();
Pascal\YouTubeApiHelper::authorize_service_account($client,OAUTH2_SERVICE_ACCOUNT_NAME,OAUTH2_KEY_FILE);
$youtube = new Google_Service_YouTube($client);

$channelId = '';

$videos = Pascal\YouTubeApiHelper::get_videos_from_channel($youtube, $channelId, new \Pascal\ContentOwner(CONTENT_OWNER));
foreach($videos as $video) {
    echo "Video Id: ".$video['id']['videoId']." - Title ".$video['snippet']['title']."\n\r <br>";
}