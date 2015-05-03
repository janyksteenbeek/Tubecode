<?php namespace Pascal;

use Google_Auth_AssertionCredentials;
use Google_Client;
use Google_Http_MediaFileUpload;
use Google_Service_Exception;
use Google_Service_YouTube;
use Google_Service_YouTube_Video;
use Google_Service_YouTube_VideoSnippet;
use Google_Service_YouTube_VideoStatus;
use Google_Service_YouTubePartner;
use Google_Service_YouTubePartner_Asset;
use Google_Service_YouTubePartner_AssetMatchPolicy;
use Google_Service_YouTubePartner_Claim;
use Google_Service_YouTubePartner_Metadata;
use Google_Service_YouTubePartner_Policy;
use Google_Service_YouTubePartner_Reference;
use Google_Service_YouTubePartner_RightsOwnership;
use Google_Service_YouTubePartner_TerritoryOwners;

class YouTubeApiHelper {

    public static function authorize_service_account(Google_Client $client, $service_account_name, $key_file)
    {
        $key = file_get_contents($key_file);
        $cred = new Google_Auth_AssertionCredentials(
            $service_account_name,
            ['https://www.googleapis.com/auth/youtubepartner'],
            $key);
        $client->setAssertionCredentials($cred);

        if($client->getAuth()->isAccessTokenExpired())
        {
            $client->getAuth()->refreshTokenWithAssertion($cred);
        }
    }

    public static function upload_video(Google_Client $client, Google_Service_YouTube $youtube_service, $video_file,
                                 $video_title, $video_description, $video_tags,
                                 $video_category, $video_public, ContentOwner $content_owner, $channel)
    {


        $optParams = [
            'onBehalfOfContentOwner'        => $content_owner->getId(),
            'onBehalfOfContentOwnerChannel' => $channel
        ];

        $snippet = new Google_Service_YouTube_VideoSnippet();
        $snippet->setTitle($video_title);
        $snippet->setDescription($video_description);
        $snippet->setTags($video_tags);
        $snippet->setCategoryId($video_category);

        $status = new Google_Service_YouTube_VideoStatus();
        $status->setPrivacyStatus($video_public);

        $video = new Google_Service_YouTube_Video();
        $video->setSnippet($snippet);
        $video->setStatus($status);

        $chunkSizeBytes = 1 * 1024 * 1024;

        $client->setDefer(true);

        $insertRequest = $youtube_service->videos->insert("status,snippet", $video, $optParams);

        $media = new Google_Http_MediaFileUpload(
            $client,
            $insertRequest,
            'video/*',
            null,
            true,
            $chunkSizeBytes
        );
        $media->setFileSize(filesize($video_file));

        $status = false;
        $handle = fopen($video_file, "rb");
        while( ! $status && ! feof($handle))
        {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }

        fclose($handle);

        $client->setDefer(false);

        return $status['id'];
    }

    public static function list_content_owners(Google_Service_YouTubePartner $youtube_partner)
    {
        $optParams = ['fetchMine' => true];

        $coListResponse = $youtube_partner->contentOwners->listContentOwners($optParams);

        $contentOwners = [];

        foreach($coListResponse['items'] as $contentOwner)
        {
            $primaryNotificationEmails = [];
            $conflictNotificationEmail = null;
            $disputeNotificationEmails = [];
            $fingerprintReportNotificationEmails = [];
            if(array_key_exists('primaryNotificationEmails', $contentOwner))
                $primaryNotificationEmails = $contentOwner['primaryNotificationEmails'];

            if(array_key_exists('conflictNotificationEmail', $contentOwner))
                $conflictNotificationEmail = $contentOwner['conflictNotificationEmail'];

            if(array_key_exists('disputeNotificationEmails', $contentOwner))
                $disputeNotificationEmails = $contentOwner['disputeNotificationEmails'];

            if(array_key_exists('fingerprintReportNotificationEmails', $contentOwner))
                $fingerprintReportNotificationEmails = $contentOwner['fingerprintReportNotificationEmails'];

            $contentOwners[] = new ContentOwner($contentOwner['id'],
                $contentOwner['displayName'],
                $primaryNotificationEmails,
                $conflictNotificationEmail,
                $disputeNotificationEmails,
                $fingerprintReportNotificationEmails);
        }

        return $contentOwners;
    }

    public static function create_asset(Google_Service_YouTubePartner $youtube_partner, $asset_title, ContentOwner $content_owner, $type = 'web')
    {
        $optParams = [
            'onBehalfOfContentOwner' => $content_owner->getId(),
        ];

        $asset = new Google_Service_YouTubePartner_Asset();
        $metadata = new Google_Service_YouTubePartner_Metadata();
        $metadata->setTitle($asset_title);
        $asset->setMetadata($metadata);
        $asset->setType($type);

        $assetInsertResponse = $youtube_partner->assets->insert($asset,
            $optParams);

        return $assetInsertResponse['id'];
    }

    public static function set_asset_ownership(Google_Service_YouTubePartner $youtube_partner, $asset_id,
                                        $asset_ownership_type, $asset_ownership_territorries, ContentOwner $content_owner)
    {
        $optParams = [
            'onBehalfOfContentOwner' => $content_owner->getId(),
        ];

        $owners = new Google_Service_YouTubePartner_TerritoryOwners();
        $owners->setOwner($content_owner->getId());
        $owners->setType($asset_ownership_type);
        $owners->setRatio(100);
        $owners->setTerritories($asset_ownership_territorries);

        $ownership = new Google_Service_YouTubePartner_RightsOwnership();
        $ownership->setGeneral([$owners]);

        $ownershipUpdateResponse = $youtube_partner->ownership->update(
            $asset_id,
            $ownership,
            $optParams
        );

        return $ownershipUpdateResponse;

    }

    public static function get_named_policies(Google_Service_YouTubePartner $youtube_partner, ContentOwner $content_owner)
    {
        $allNamedPolicies = [];

        $optParams = [
            'onBehalfOfContentOwner' => $content_owner->getId()
        ];

        $policiesResponse = $youtube_partner->policies->listPolicies($optParams);

        foreach($policiesResponse as $policy)
        {
            $allNamedPolicies[$policy['name']] = $policy['id'];
        }

        return $allNamedPolicies;
    }


    public static function create_claim(Google_Service_YouTubePartner $youtube_partner, $asset_id, $video_id, $claim_policy_id, ContentOwner $content_owner)
    {
        $optParams = [
            'onBehalfOfContentOwner' => $content_owner->getId()
        ];

        $usage_policy = new Google_Service_YouTubePartner_Policy();
        $usage_policy->setId($claim_policy_id);


        $claim = new Google_Service_YouTubePartner_Claim();
        $claim->setAssetId($asset_id);
        $claim->setVideoId($video_id);
        $claim->setPolicy($usage_policy);
        $claim->setContentType('audiovisual');

        $claimInsertResponse = $youtube_partner->claims->insert($claim, $optParams);

        return $claimInsertResponse['id'];
    }

    public static function create_reference_file(Google_Service_YouTubePartner $youtube_partner, Google_Service_YouTube $youtube, $claim_id, $video_id, ContentOwner $content_owner)
    {
        $optParams = [
            'onBehalfOfContentOwner' => $content_owner->getId(),
        ];

        $reference = new Google_Service_YouTubePartner_Reference();
        $reference->setClaimId($claim_id);
        $reference->setContentType('audiovisual');

        $status = false;
        while( ! $status)
        {
            $optParams = ['id' => $video_id];

            $youtubeVideoResponse = $youtube->videos->listVideos('snippet', $optParams);

            if($youtubeVideoResponse['status']['uploadStatus'] == 'uploaded')
            {
                $status = true;
            }
        }

        try {
            $youtube_partner->references->insert($reference, $optParams);
        } catch(Google_Service_Exception $e)
        {
            die($e);
        }

    }

    public static function set_match_policy(Google_Service_YouTubePartner $youtube_partner, $asset_id, $asset_match_policy_id, ContentOwner $content_owner)
    {
        $optParams = [
            'onBehalfOfContentOwner' => $content_owner->getId()
        ];

        $match_policy = new Google_Service_YouTubePartner_AssetMatchPolicy();
        $match_policy->setPolicyId($asset_match_policy_id);

        $youtube_partner->assetMatchPolicy->update($asset_id, $match_policy, $optParams);
    }

    public static function list_channels($youtube, ContentOwner $content_owner)
    {
        $channels = [];
        $pageToken = '';

        do
        {
            $opt = [
                'onBehalfOfContentOwner' => $content_owner->getId(),
                'pageToken'              => $pageToken,
                'managedByMe'            => 'mine'
            ];

            $channelListResponse = $youtube->channels->listChannels('snippet', $opt);

            array_push($channels, $channelListResponse['items']);

            $pageToken = $channelListResponse['nextPageToken'];
        } while($pageToken);

        return $channels;

    }

    public static function get_videos_from_channel(Google_Service_YouTube $youtube_client, $channel, ContentOwner $content_owner)
    {
        $videos = [];
        $pageToken = '';
        $params = [
            'onBehalfOfContentOwner' => $content_owner->getId(),
            'forContentOwner'        => 'true',
            'maxResults'             => 50,
            'pageToken'              => $pageToken,
            'channelId'              => $channel,
            'type'                   => 'video'
        ];

        do
        {
            $search_response = $youtube_client->search->listSearch('snippet', $params);

            foreach($search_response['items'] as $video)
            {
                $videos[] = $video;
            }

            $pageToken = $search_response['nextPageToken'];

            $params['pageToken'] = $search_response['nextPageToken'];

        } while($pageToken);

        return $videos;
    }

    public static function sort_videos_claiming_status(Google_Service_YouTubePartner $youtube_partner_client, $video_ressource_array, ContentOwner $content_owner)
    {
        $unclaimed = [];
        $claimed_by_third_party = [];
        $claimed_by_me = [];
        $third_party_names = [];

        $video_id_array = [];

        foreach($video_ressource_array as $video)
        {
            $video_id_array[] = $video['id']['videoId'];
        }

        while(count($video_id_array))
        {
            $this_call_video_ids = array_splice($video_id_array, 0, 50);

            do
            {
                $opt = [
                    'onBehalfOfContentOwner' => $content_owner->getId(),
                    'videoId'                => implode(",", $this_call_video_ids)
                ];

                $claimSearch_response = $youtube_partner_client->claimSearch->listClaimSearch($opt);

                foreach($claimSearch_response['items'] as $claimSnippet)
                {
                    if($claimSnippet['thirdPartyClaim'])
                    {
                        $claimed_by_third_party[] = $claimSnippet['videoId'];
                        $assetId = $claimSnippet['assetId'];

                        $asset = $youtube_partner_client->assets->get($assetId, [
                            'onBehalfOfContentOwner' => $content_owner->getId(),
                            'fetchOwnership'         => 'effective']);

                        $content_owner_id = $asset['ownershipEffective']['general'][0]['owner'];

                        $content_owner_response = $youtube_partner_client->contentOwners->listContentOwners([
                            'id'                     => $content_owner_id,
                            'onBehalfOfContentOwner' => $content_owner->getId()]);

                        $third_party_names[$claimSnippet['videoId']] = $content_owner_response['items'][0]['displayName'];

                    }
                    else
                    {
                        $claimed_by_me[] = $claimSnippet['videoId'];
                    }
                }

                $opt['pageToken'] = $claimSearch_response['nextPageToken'];
            } while($claimSearch_response['nextPageToken']);

            // Add to unclaimed all the IDs of videos which do not appear in claimed_by_me nor claimed_by_third_party
            $unclaimed = array_merge($unclaimed,
                array_filter($this_call_video_ids, function ($video_id) use ($claimed_by_me, $claimed_by_third_party)
                {
                    return ! (in_array($video_id, $claimed_by_me) || in_array($video_id, $claimed_by_third_party));
                }));
        }

        return [
            "unclaimed"              => $unclaimed,
            "claimed_by_third_party" => $claimed_by_third_party,
            "claimed_by_me"          => $claimed_by_me,
            "third_party_names"      => $third_party_names,
        ];
    }


}