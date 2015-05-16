<?php namespace Pascal\YouTube;

use Google_Http_MediaFileUpload;
use Google_Service_YouTube_Video;
use Google_Service_YouTube_VideoSnippet;
use Google_Service_YouTube_VideoStatus;
use Pascal\Exceptions\InvalidChannelException;
use Pascal\Resources\Channel;
use Pascal\Resources\UploadableFile;

class VideoUploader {

    protected $tags = [];
    protected $title = "My Video uploaded via API";
    protected $description = "This is my Video, I uploaded it via the Google API";
    protected $category = 22;
    protected $privacyStatus = "Public";
    protected $videoFile;
    protected $recipient = [];

    /**
     * @class Pascal\YouTube\Api
     */
    private $api;

    /**
     * @var int
     */
    private $chunkSizeBytes;

    /**
     * Initial Video Uploader
     *
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;

        $this->chunkSizeBytes = 1 * 1024 * 1024;

        return $this;
    }

    /**
     * Set the Video that should be uploaded
     *
     * @param UploadableFile $videoFile
     */
    public function setVideo(UploadableFile $videoFile)
    {
        //TODO: Validation
        $this->videoFile = $videoFile;

        return $this;
    }

    /**
     * Alias for setting Tags
     *
     * @param $tag
     */
    public function addTag($tag)
    {
        return $this->setTags($tag);
    }

    /**
     * set Tags
     *
     * @param array $tags
     */
    public function setTags($tag)
    {
        if(is_array($tag))
        {
            foreach($tag as $t)
            {
                $this->addTag($t);
            }
        }

        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Set the Title of the Video
     *
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the Description of the Video
     *
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the Numeric video category. See https://developers.google.com/youtube/v3/docs/videoCategories/list
     *
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Set the Privacy Status (Public, Unlisted or Private)
     *
     * @param mixed $privacyStatus
     */
    public function setPrivacyStatus($privacyStatus)
    {
        $this->privacyStatus = $privacyStatus;

        return $this;
    }

    /**
     * Set the meta options for a Video
     *
     * @param array $options
     */
    public function meta(array $options)
    {
        foreach($options as $key => $option)
        {
            if(property_exists($this, $key))
            {
                $this->set{ucfirst($key)} = $option;
            }
        }

        return $this;
    }

    /**
     * Upload the video to this Channel
     *
     * @param mixed $channel
     */
    public function to($channel)
    {
        if(is_array($channel))
        {
            foreach($channel as $c)
            {
                $this->to($c);
            }
        }

        if($channel instanceof Channel)
        {
            $this->recipient[] = $channel;

            return $this;
        }

        throw new InvalidChannelException("The Channel you passed to upload the video to was invalid.");
    }

    /**
     * Start uploading the Video
     *
     * @return array
     */
    public function start()
    {
        //TODO: Queue the upload process?

        $response = [];
        $optParams = [
            'onBehalfOfContentOwner' => $this->api->getContentOwner()->getId(),
        ];

        foreach($this->recipient as $channel)
        {
            $optParams['onBehalfOfContentOwnerChannel'] = $channel->getId();

            $video = $this->createYouTubeVideo(
                $this->createVideoSnippet(),
                $this->createVideoStatus()
            );

            $this->api->getClient()->setDefer(true);

            $status = $this->execute($video, $optParams);

            $this->api->getClient()->setDefer(false);

            //TODO: return Video Resource
            $response[] = $status['id'];
        }

        return $response;
    }

    /**
     * Create a Google YouTube Video Snippet resource
     *
     * @return Google_Service_YouTube_VideoSnippet
     */
    private function createVideoSnippet()
    {
        $snippet = new Google_Service_YouTube_VideoSnippet();
        $snippet->setTitle($this->title);
        $snippet->setDescription($this->description);
        $snippet->setTags($this->tags);
        $snippet->setCategoryId($this->category);

        return $snippet;
    }

    /**
     * Create a Google YouTube Video Status resource
     *
     * @return Google_Service_YouTube_VideoStatus
     */
    private function createVideoStatus()
    {
        $status = new Google_Service_YouTube_VideoStatus();
        $status->setPrivacyStatus($this->privacyStatus);

        return $status;
    }

    /**
     * Create a Google YouTube Video resources
     *
     * @param $snippet
     * @param $status
     *
     * @return Google_Service_YouTube_Video
     */
    private function createYouTubeVideo($snippet, $status)
    {
        $video = new Google_Service_YouTube_Video();
        $video->setSnippet($snippet);
        $video->setStatus($status);

        return $video;
    }

    /**
     * Execute the Uploading
     *
     * @param $video
     * @param $optParams
     * @param $video_file
     * @param $chunkSizeBytes
     *
     * @return bool|mixed|null
     */
    private function execute($video, $optParams)
    {
        $media = new Google_Http_MediaFileUpload(
            $this->api->getClient(),
            $this->api->getYoutube()->videos->insert("status,snippet", $video, $optParams),
            'video/*',
            null,
            true,
            $this->chunkSizeBytes
        );

        $media->setFileSize($this->videoFile->getSize());

        $status = false;
        $handle = fopen($this->videoFile->getPath(), "rb");
        while( ! $status && ! feof($handle))
        {
            $chunk = fread($handle, $this->chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }
        fclose($handle);

        return $status;
    }

}