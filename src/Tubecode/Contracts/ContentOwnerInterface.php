<?php namespace Tubecode\Contracts;

interface ContentOwnerInterface {

    public function __construct(\Google_Service_YouTubePartner_ContentOwner $contentOwner, \Google_Client $client);

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getDisplayName();

    /**
     * @return string
     */
    public function getConflictNotificationEmail();

    /**
     * @return array|null
     */
    public function getDisputeNotificationEmails();

    /**
     * @return array|null
     */
    public function getFingerprintReportNotificationEmails();

    /**
     * @return array|null
     */
    public function getPrimaryNotificationEmails();
}