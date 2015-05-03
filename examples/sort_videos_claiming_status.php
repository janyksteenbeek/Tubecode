<?php

DEFINE("OAUTH2_SERVICE_ACCOUNT_NAME", 	'YOUR GOOGLE SERVICE ACCOUNT');
DEFINE("OAUTH2_KEY_FILE", 				__DIR__.'/storage/key.p12');
DEFINE("CONTENT_OWNER",					'YOUR CONTENT OWNER ID');


//Prepare the Google Client
$client = new Google_Client();
Pascal\YouTubeApiHelper::authorize_service_account($client,OAUTH2_SERVICE_ACCOUNT_NAME,OAUTH2_KEY_FILE);
$youtube = new Google_Service_YouTube($client);
$partner = new Google_Service_YouTubePartner($client);

$videos = Pascal\YouTubeApiHelper::get_videos_from_channel($youtube, $channelId, new \Pascal\ContentOwner(CONTENT_OWNER));
$result = Pascal\YouTubeApiHelper::sort_videos_claiming_status($partner, $videos, new \Pascal\ContentOwner(CONTENT_OWNER)) ;

echo "Unclaimed = " . count($result['unclaimed']) . "\n\r <br>";
echo "Claimed by third party = ".count($result['claimed_by_third_party'])."\n\r <br>";
echo "Claimed by me = ".count($result['claimed_by_me'])."\n\r <br>";
echo "<br>";
foreach($result['third_party_names'] as $video => $contentOwner){
    echo "3. Party Claim on: {$video} by {$contentOwner} <br>";
}