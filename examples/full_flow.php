<?php

DEFINE("OAUTH2_SERVICE_ACCOUNT_NAME", 	'YOUR GOOGLE SERVICE ACCOUNT');
DEFINE("OAUTH2_KEY_FILE", 				__DIR__.'/storage/key.p12');
DEFINE("CONTENT_OWNER",					'YOUR CONTENT OWNER ID');


//Prepare the Google Client
$client = new Google_Client();
Pascal\YouTubeApiHelper::authorize_service_account($client,OAUTH2_SERVICE_ACCOUNT_NAME,OAUTH2_KEY_FILE);
$youtube = new Google_Service_YouTube($client);
$partner = new Google_Service_YouTubePartner($client);

$video = [
    'path' => '/Users/you/files/video.flv',
    'description' => 'Uploaded via API',
    'title' => 'API full flow example',
    'tags' => ['thecakeisalie'],
    'visibility' => 'public',
    'category' => '22',
];

$YOUTUBE_CHANNEL_ID = '';
$POLICY_ID = '';

$video_id = Pascal\YouTubeApiHelper::upload_video(
    $client,
    $youtube,
    $video['path'],
    $video['title'],
    $video['description'],
    $video['tags'],
    $video['category'],
    $video['visibility'],
    new \Pascal\ContentOwner(CONTENT_OWNER),
    $YOUTUBE_CHANNEL_ID);

if(is_null($video_id))  {
    die("Could not upload video");
}

echo "<strong>Video Uploaded.</strong> ID = {$video_id} \n\r <br>";

$asset_id = Pascal\YouTubeApiHelper::create_asset(
    $partner,
    $video['title'],
    CONTENT_OWNER
);

echo "Created asset $asset_id\n\r <br>";

$asset_ownership_territorries = [];
$asset_ownership_type='exclude';

Pascal\YouTubeApiHelper::set_asset_ownership(
    $partner,
    $asset_id,
    $asset_ownership_type,
    $asset_ownership_territorries,
    new \Pascal\ContentOwner(CONTENT_OWNER)
);

echo "Done updating Asset {$asset_id} \n\r <br>";

$claim_id = Pascal\YouTubeApiHelper::create_claim(
    $partner,
    $asset_id,
    $video_id,
    $POLICY_ID,
    new \Pascal\ContentOwner(CONTENT_OWNER)
);

echo "Created claim. ID = $claim_id \n\r <br>";

if(Pascal\YouTubeApiHelper::set_match_policy($partner,$asset_id,$POLICY_ID,new \Pascal\ContentOwner(CONTENT_OWNER)) < 0) {
    echo "Error setting match policy \n\r <br>";
} else {
    Pascal\YouTubeApiHelper::create_reference_file($partner,$youtube,$claim_id,$video_id,new \Pascal\ContentOwner(CONTENT_OWNER));
    echo "Created Reference file \n\r <br>";
}

echo "<br><br><br> Done! \n\r <br>";