<?php

namespace Tubecode;

use Tubecode\Collections\PartneredChannels;
use Tubecode\Contracts\ContentOwnerInterface;

class ContentOwner implements ContentOwnerInterface
{

    /**
     * @var \Google_Client
     */
    private $client;
    private $primaryNotificationEmails;
    private $conflictNotificationEmail;
    private $fingerprintReportNotificationEmails;
    private $displayName;
    private $disputeNotificationEmails;
    private $id;

    /**
     * @param \Google_Service_YouTubePartner_ContentOwner $contentOwner
     * @param \Google_Client                              $client
     */
    public function __construct(\Google_Service_YouTubePartner_ContentOwner $contentOwner, \Google_Client $client)
    {
        $this->assignProperties($contentOwner);
        $this->client = $client;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function getConflictNotificationEmail()
    {
        return $this->conflictNotificationEmail;
    }

    public function getDisputeNotificationEmails()
    {
        return $this->disputeNotificationEmails;
    }

    public function getFingerprintReportNotificationEmails()
    {
        return $this->fingerprintReportNotificationEmails;
    }

    public function getPrimaryNotificationEmails()
    {
        return $this->primaryNotificationEmails;
    }

    public function partneredChannels()
    {
        return new PartneredChannels($this->client, $this);
    }

    /**
     * @param \Google_Service_YouTubePartner_ContentOwner $contentOwner
     */
    private function assignProperties(\Google_Service_YouTubePartner_ContentOwner $contentOwner)
    {
        $this->primaryNotificationEmails = $contentOwner->primaryNotificationEmails;
        $this->conflictNotificationEmail = $contentOwner->conflictNotificationEmail;
        $this->fingerprintReportNotificationEmails = $contentOwner->fingerprintReportNotificationEmails;
        $this->displayName = $contentOwner->displayName;
        $this->disputeNotificationEmails = $contentOwner->disputeNotificationEmails;
        $this->id = $contentOwner->id;
    }

}