<?php

namespace ApiExport\Module\App\Model\DTO;

/**
 * Class FeedItem.
 */
class FeedItem
{
    /** @var  int */
    private $id;
    /** @var  int */
    private $feedId;
    /** @var  string */
    private $hash;
    /** @var  string */
    private $title;
    /** @var  string */
    private $categories;
    /** @var  string */
    private $authorName;
    /** @var  string */
    private $authorUri;
    /** @var  string */
    private $uri;
    /** @var  \DateTime */
    private $updateDate;
    /** @var  string */
    private $resume;
    /** @var  string */
    private $extract;
    /** @var  bool */
    private $isEnabled = false;
    /** @var bool  */
    private $isViewed = false;
    /** @var bool  */
    private $isApproved = false;

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
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getFeedId()
    {
        return $this->feedId;
    }

    /**
     * @param int $feedId
     */
    public function setFeedId($feedId)
    {
        $this->feedId = $feedId;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param string $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * @param string $authorName
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    }

    /**
     * @return string
     */
    public function getAuthorUri()
    {
        return $this->authorUri;
    }

    /**
     * @param string $authorUri
     */
    public function setAuthorUri($authorUri)
    {
        $this->authorUri = $authorUri;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
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
        $this->updateDate = $updateDate;
    }

    /**
     * @return string
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * @param string $resume
     */
    public function setResume($resume)
    {
        $this->resume = $resume;
    }

    /**
     * @return string
     */
    public function getExtract()
    {
        return $this->extract;
    }

    /**
     * @param string $extract
     */
    public function setExtract($extract)
    {
        $this->extract = $extract;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     */
    public function setEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }

    /**
     * @return bool
     */
    public function isViewed()
    {
        return $this->isViewed;
    }

    /**
     * @param bool $isViewed
     */
    public function setViewed($isViewed)
    {
        $this->isViewed = $isViewed;
    }

    /**
     * @return bool
     */
    public function isApproved()
    {
        return $this->isApproved;
    }

    /**
     * @param bool $isApproved
     */
    public function setApproved($isApproved)
    {
        $this->isApproved = $isApproved;
    }
}
