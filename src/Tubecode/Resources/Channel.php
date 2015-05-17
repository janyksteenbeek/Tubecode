<?php namespace Tubecode\Resources;

class Channel {

    /**
     * @var string
     */
    private $id;

    /**
     * @var null
     */
    private $title;

    /**
     * @var null
     */
    private $description;

    /**
     * @var null
     */
    private $createdAt;

    /**
     * @var array
     */
    private $avatar;

    /**
     * @var null
     */
    private $country;

    /**
     * @var null
     */
    private $googlePlusUserId;

    /**
     * @var array
     */
    private $statistics;

    /**
     * @var array
     */
    private $topicDetails;

    /**
     * @var null
     */
    private $privacyStatus;

    /**
     * @var null
     */
    private $isLinked;

    /**
     * @var null
     */
    private $longUploadsStatus;

    /**
     * @var array
     */
    private $channelBrandingSettings;

    /**
     * @var array
     */
    private $imageBrandingSettings;

    /**
     * @var array
     */
    private $auditDetails;

    /**
     * @var ContentOwner
     */
    private $contentOwner;

    /**
     * @param              $id
     * @param null         $title
     * @param null         $description
     * @param null         $createdAt
     * @param array        $avatar
     * @param null         $country
     * @param null         $googlePlusUserId
     * @param array        $statistics
     * @param array        $topicDetails
     * @param null         $privacyStatus
     * @param null         $isLinked
     * @param null         $longUploadsStatus
     * @param array        $channelBrandingSettings
     * @param array        $imageBrandingSettings
     * @param array        $auditDetails
     * @param ContentOwner $contentOwner
     */
    public function __construct(
        $id,
        $title                  = null,
        $description            = null,
        $createdAt              = null,
        array $avatar           = null,
        $country                = null,
        $googlePlusUserId       = null,
        array $statistics       = null,
        array $topicDetails     = null,
        $privacyStatus          = null,
        $isLinked               = null,
        $longUploadsStatus      = null,
        array $channelBrandingSettings  = null,
        array $imageBrandingSettings    = null,
        array $auditDetails             = null,
        ContentOwner $contentOwner      = null
        )
    {

        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->avatar = $avatar;
        $this->country = $country;
        $this->googlePlusUserId = $googlePlusUserId;
        $this->statistics = $statistics;
        $this->topicDetails = $topicDetails;
        $this->privacyStatus = $privacyStatus;
        $this->isLinked = $isLinked;
        $this->longUploadsStatus = $longUploadsStatus;
        $this->channelBrandingSettings = $channelBrandingSettings;
        $this->imageBrandingSettings = $imageBrandingSettings;
        $this->auditDetails = $auditDetails;
        $this->contentOwner = $contentOwner;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param null $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param null $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return array
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param array $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param null $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return null
     */
    public function getGooglePlusUserId()
    {
        return $this->googlePlusUserId;
    }

    /**
     * @param null $googlePlusUserId
     */
    public function setGooglePlusUserId($googlePlusUserId)
    {
        $this->googlePlusUserId = $googlePlusUserId;
    }

    /**
     * @return array
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param array $statistics
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
    }

    /**
     * @return array
     */
    public function getTopicDetails()
    {
        return $this->topicDetails;
    }

    /**
     * @param array $topicDetails
     */
    public function setTopicDetails($topicDetails)
    {
        $this->topicDetails = $topicDetails;
    }

    /**
     * @return null
     */
    public function getPrivacyStatus()
    {
        return $this->privacyStatus;
    }

    /**
     * @param null $privacyStatus
     */
    public function setPrivacyStatus($privacyStatus)
    {
        $this->privacyStatus = $privacyStatus;
    }

    /**
     * @return null
     */
    public function getIsLinked()
    {
        return $this->isLinked;
    }

    /**
     * @param null $isLinked
     */
    public function setIsLinked($isLinked)
    {
        $this->isLinked = $isLinked;
    }

    /**
     * @return null
     */
    public function getLongUploadsStatus()
    {
        return $this->longUploadsStatus;
    }

    /**
     * @param null $longUploadsStatus
     */
    public function setLongUploadsStatus($longUploadsStatus)
    {
        $this->longUploadsStatus = $longUploadsStatus;
    }

    /**
     * @return array
     */
    public function getChannelBrandingSettings()
    {
        return $this->channelBrandingSettings;
    }

    /**
     * @param array $channelBrandingSettings
     */
    public function setChannelBrandingSettings($channelBrandingSettings)
    {
        $this->channelBrandingSettings = $channelBrandingSettings;
    }

    /**
     * @return array
     */
    public function getImageBrandingSettings()
    {
        return $this->imageBrandingSettings;
    }

    /**
     * @param array $imageBrandingSettings
     */
    public function setImageBrandingSettings($imageBrandingSettings)
    {
        $this->imageBrandingSettings = $imageBrandingSettings;
    }

    /**
     * @return array
     */
    public function getAuditDetails()
    {
        return $this->auditDetails;
    }

    /**
     * @param array $auditDetails
     */
    public function setAuditDetails($auditDetails)
    {
        $this->auditDetails = $auditDetails;
    }

    /**
     * @return ContentOwner
     */
    public function getContentOwner()
    {
        return $this->contentOwner;
    }

    /**
     * @param ContentOwner $contentOwner
     */
    public function setContentOwner($contentOwner)
    {
        $this->contentOwner = $contentOwner;
    }

}