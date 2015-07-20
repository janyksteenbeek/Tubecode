<?php

namespace Tubecode\Collections;

use Tubecode\ContentOwner;
use Tubecode\Contracts\ContentOwnerInterface;
use Tubecode\Models\Channel;

class LinkedChannels
{
    private $channels = [];

    /**
     * @param \Google_Client $client
     */
    public function __construct(\Google_Client $client, ContentOwnerInterface $contentOwner)
    {
        $data = new \Google_Service_YouTube($client);

        $channels = $data->channels->listChannels(
            'id, snippet, contentDetails, statistics, topicDetails, invideoPromotion',
            [
                'managedByMe' => true,
                'onBehalfOfContentOwner' => $contentOwner->getId()
            ]
        );

        foreach($channels->getItems() as $channel)
        {
            $this->channels[$channel->getId()] = new Channel($channel, $client);
        }

    }

    public function all()
    {
        return $this->channels;
    }

    public function get($id)
    {
        return $this->channels[$id];
    }

    public function count()
    {
        return count($this->channels);
    }

}