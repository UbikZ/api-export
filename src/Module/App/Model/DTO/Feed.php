<?php

namespace ApiExport\Module\App\Model\DTO;

/**
 * Class Feed.
 */
class Feed
{
    /** @var  int */
    private $id;
    /** @var  string */
    private $label;
    /** @var  string */
    private $url;
    /** @var  FeedType */
    private $type;
    /** @var  \DateTime */
    private $updateDate;
    /** @var  bool */
    private $isEnabled;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = intval($id);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return FeedType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param FeedType $type
     */
    public function setType(FeedType $type)
    {
        $this->type = $type;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * @param \DateTime $updateDate
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = new \DateTime($updateDate);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param $isEnabled
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = boolval($isEnabled);
    }
}
