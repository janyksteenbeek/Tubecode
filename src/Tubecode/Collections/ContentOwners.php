<?php

namespace Tubecode\Collections;

use Tubecode\Models\ContentOwner;
use Tubecode\Contracts\ContentOwnerInterface;

class ContentOwners
{

    /**
     * @var \Google_Client
     */
    private $client;

    /**
     * @var ContentOwnerInterface
     */
    private $contentOwner = [];

    /**
     * @param \Google_Client $client
     * @param null           $content_owner_id
     *
     * @return ContentOwners|ContentOwnerInterface
     */
    public static function create(\Google_Client $client, $content_owner_id = null)
    {
        $partner = new \Google_Service_YouTubePartner($client);

        if(is_null($content_owner_id))
        {
            return new ContentOwners($client);
        }

        $response = $partner->contentOwners->listContentOwners(['id' => $content_owner_id]);

        return self::createContentOwnerInstance($response['items'][0], $client);
    }

    /**
     * @param \Google_Client $client
     */
    public function __construct(\Google_Client $client)
    {
        $this->client = $client;

        $partner = new \Google_Service_YouTubePartner($this->client);

        $this->loadInitialContentOwners($partner);
    }

    /**
     * @param null $id
     * @return ContentOwnerInterface
     */
    public function get($id = null)
    {
        return $this->contentOwner[$id];
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->contentOwner;
    }

    /**
     * @param $partner \Google_Service_YouTubePartner
     */
    private function loadInitialContentOwners(\Google_Service_YouTubePartner $partner)
    {
        $response = $partner->contentOwners->listContentOwners(['fetchMine' => true]);

        foreach($response->getItems() as $contentOwner)
        {
            $this->contentOwner[$contentOwner->getId()] = $this->createContentOwner($contentOwner);
        }
    }

    /**
     * @param $contentOwner
     *
     * @return ContentOwnerInterface
     */
    private function createContentOwner(\Google_Service_YouTubePartner_ContentOwner $contentOwner)
    {
        return self::createContentOwnerInstance($contentOwner, $this->client);
    }

    /**
     * @param $contentOwner
     *
     * @return ContentOwnerInterface
     */
    private static function createContentOwnerInstance(\Google_Service_YouTubePartner_ContentOwner $contentOwner, \Google_Client $client)
    {
        return new ContentOwner($contentOwner, $client);
    }
}