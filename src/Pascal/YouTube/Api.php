<?php namespace Pascal\YouTube;

use Google_Client;
use Google_Service_YouTube;
use Google_Service_YouTubePartner;
use Pascal\Resources\ContentOwner;
use Pascal\Resources\UploadableFile;

class Api {

    /**
     * @var Google_Client
     */
    private $client;

    /**
     * @var Google_Service_YouTube
     */
    private $youtube;

    /**
     * @var Google_Service_YouTubePartner
     */
    private $partner;

    /**
     * @var ContentOwner
     */
    private $contentOwner;

    /**
     * Initial API Object
     *
     * @param Google_Client                 $client
     * @param Google_Service_YouTube        $youtube
     * @param Google_Service_YouTubePartner $partner
     */
    public function __construct(
        Google_Client $client,
        Google_Service_YouTube $youtube,
        Google_Service_YouTubePartner $partner,
        ContentOwner $contentOwner
    ) {
        $this->client = $client;
        $this->youtube = $youtube;
        $this->partner = $partner;
        $this->contentOwner = $contentOwner;
    }




    public function uploadVideo(UploadableFile $video)
    {
        return (new VideoUploader($this))->setVideo($video);
    }

    /**
     * @return Google_Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return Google_Service_YouTube
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * @return Google_Service_YouTubePartner
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * @return ContentOwner
     */
    public function getContentOwner()
    {
        return $this->contentOwner;
    }

}