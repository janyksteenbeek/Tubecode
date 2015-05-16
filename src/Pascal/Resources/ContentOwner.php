<?php namespace Pascal;


class ContentOwner {

    private $id;
    private $displayName;
    private $primaryNotificationEmails = [];
    private $conflictNotificationEmail;
    private $disputeNotificationEmails = [];
    private $fingerprintReportNotificationEmails = [];

    function __construct($id, $displayName = null, $primary = [], $conflict = null, $dispute = [], $fingerprint = [])
    {
        $this->id = $id;
        $this->displayName = $displayName;
        $this->primaryNotificationEmails = $primary;
        $this->conflictNotificationEmail = $conflict;
        $this->disputeNotificationEmails = $dispute;
        $this->fingerprintReportNotificationEmails = $fingerprint;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getPrimaryNotificationEmails()
    {
        return $this->primaryNotificationEmails;
    }

    /**
     * @return mixed
     */
    public function getConflictNotificationEmail()
    {
        return $this->conflictNotificationEmail;
    }

    /**
     * @return array
     */
    public function getDisputeNotificationEmails()
    {
        return $this->disputeNotificationEmails;
    }

    /**
     * @return array
     */
    public function getFingerprintReportNotificationEmails()
    {
        return $this->fingerprintReportNotificationEmails;
    }

    /**
     * @return null
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

}