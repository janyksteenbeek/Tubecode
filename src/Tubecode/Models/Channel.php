<?php

namespace Tubecode\Models;

use Tubecode\Contracts\ChannelInterface;
use Tubecode\Exceptions\UnknowsChannelProperty;
use Tubecode\Models\ChannelResources\BrandingSettings;
use Tubecode\Models\ChannelResources\ContentDetails;
use Tubecode\Models\ChannelResources\InvideoPromotion;
use Tubecode\Models\channelResources\Snippet;
use Tubecode\Models\ChannelResources\Statistics;
use Tubecode\Models\ChannelResources\Status;
use Tubecode\Models\ChannelResources\TopicDetails;

/**
 * @property Snippet            snippet
 * @property ContentDetails     contentDetails
 * @property Statistics         statistics
 * @property TopicDetails       topicDetails
 * @property Status             status
 * @property BrandingSettings   brandingSettings
 * @property InvideoPromotion   invideoPromotion
 */
class Channel implements ChannelInterface
{
    /**
     * @var \Google_Client
     */
    private $client;

    private $id;

    /**
     * @param \Google_Service_YouTube_Channel $channel
     * @param \Google_Client                  $client
     *
     */
    public function __construct(\Google_Service_YouTube_Channel $channel, \Google_Client $client)
    {
        $this->assignProperties($channel);
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function getContentOwner()
    {
        //TODO: return a Content Owner Model instance
    }

    /**
     * @param $key
     *
     * @return mixed
     * @throws UnknowsChannelProperty
     */
    public function __get($key)
    {
        if(property_exists($this, $key))
        {
            return $this->{$key};
        }

        $class = self::class;

        throw new UnknowsChannelProperty("The requested property {$class}@{$key} is not available");
    }

    /**
     * @param \Google_Service_YouTube_Channel $channel
     */
    private function assignProperties(\Google_Service_YouTube_Channel $channel)
    {
        $this->id               = $channel->getId();
        $this->snippet          = new Snippet($channel->getSnippet());
        $this->contentDetails   = new ContentDetails($channel->getContentDetails());
        $this->statistics       = new Statistics($channel->getStatistics());
        $this->topicDetails     = new TopicDetails($channel->getTopicDetails());
        $this->status           = new Status($channel->getStatus());
        $this->brandingSettings = new BrandingSettings($channel->getBrandingSettings());
        $this->invideoPromotion = new InvideoPromotion($channel->getInvideoPromotion());
    }
}