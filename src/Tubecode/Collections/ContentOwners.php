<?php

namespace Tubecode\Collections;

use Tubecode\ContentOwner;
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
     * @param null|string    $content_owner_id
     */
    public function __construct(\Google_Client $client, $content_owner_id = null)
    {
        $this->client = $client;

        $partner = new \Google_Service_YouTubePartner($this->client);

        if(is_null($content_owner_id))
        {
            $this->loadInitialContentOwners($partner);
        } else {
            return $this->loadContentOwner($partner);
        }

        return $this;
    }

    /**
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
            $this->contentOwner[$contentOwner['id']] = $this->createContentOwnerInstance($contentOwner);
        }
    }

    /**
     * @param \Google_Service_YouTubePartner $partner
     *
     * @return ContentOwnerInterface
     */
    private function loadContentOwner(\Google_Service_YouTubePartner $partner)
    {
        $response = $partner->contentOwners->listContentOwners(['id' => $content_owner_id, 'fetchMine' => true]);

        return $this->createContentOwnerInstance($response['items'][0]);
    }

    /**
     * @param $contentOwner
     *
     * @return ContentOwnerInterface
     */
    private function createContentOwnerInstance($contentOwner)
    {
        return new ContentOwner($contentOwner, $this->client);
    }
}