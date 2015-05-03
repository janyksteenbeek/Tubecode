<?php

DEFINE("OAUTH2_SERVICE_ACCOUNT_NAME", 	'YOUR GOOGLE SERVICE ACCOUNT');
DEFINE("OAUTH2_KEY_FILE", 				__DIR__.'/storage/key.p12');
DEFINE("CONTENT_OWNER",					'YOUR CONTENT OWNER ID');


//Prepare the Google Client
$client = new Google_Client();
Pascal\YouTubeApiHelper::authorize_service_account($client,OAUTH2_SERVICE_ACCOUNT_NAME,OAUTH2_KEY_FILE);
$youtube = new Google_Service_YouTube($client);


$YOUTUBE_CHANNEL_ID = '';
$VIDEO_PATH         = 'storage/my/video.flv';
$VIDEO_DESCRIPTION  = 'This Video was upload via the YouTube API';
$VIDEO_TITLE        = 'Test Video';
$VIDEO_TAGS         = array("thecakeisalie");
$VIDEO_PUBLIC       = "public";
$VIDEO_CATEGORY_ID  = "22";  // Numeric video category. See https://developers.google.com/youtube/v3/docs/videoCategories/list

$video_id = Pascal\YouTubeApiHelper::upload_video(
    $client,
    $youtube,
    $VIDEO_PATH,
    $VIDEO_TITLE,
    $VIDEO_DESCRIPTION,
    $VIDEO_TAGS,
    $VIDEO_CATEGORY_ID,
    $VIDEO_PUBLIC,
    CONTENT_OWNER,
    $YOUTUBE_CHANNEL_ID );

if(is_null($video_id))  {
    die("Could not upload video");
}

echo "<strong>Video Uploaded.</strong><br> ID = {$video_id} \n";