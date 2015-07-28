<?php

namespace ApiExport\Module\App\Model\DTO;

/**
 * Class FeedItem.
 */
class FeedItem
{
    /** @var  int */
    private $id;
    /** @var  Feed */
    private $feed;
    /** @var  string */
    private $hash;
    /** @var  int */
    private $offset = 0;
    /** @var  string */
    private $title;
    /** @var  string */
    private $categories;
    /** @var  string */
    private $authorName;
    /** @var  string */
    private $authorUri;
    /** @var  string */
    private $url;
    /** @var  \DateTime */
    private $updateDate;
    /** @var  string */
    private $resume;
    /** @var  string */
    private $extract;
    /** @var  bool */
    private $isEnabled = true;
    /** @var  bool */
    private $isViewed = false;
    /** @var  bool */
    private $isApproved = false;
    /** @var  bool */
    private $isReposted = false;

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
     * @return Feed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @param Feed $feed
     */
    public function setFeed(Feed $feed)
    {
        $this->feed = $feed;
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
    public function setIsEnabled($isEnabled)
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
    public function setIsViewed($isViewed)
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
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;
    }

    /**
     * @return bool
     */
    public function isReposted()
    {
        return $this->isReposted;
    }

    /**
     * @param bool $isReposted
     */
    public function setIsReposted($isReposted)
    {
        $this->isReposted = $isReposted;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'url' => $this->extract,
            'hash' => $this->hash,
            'offset' => $this->offset,
            'enabled' => intval($this->isEnabled),
            'viewed' => intval($this->isViewed),
            'approved' => intval($this->isApproved),
            'reposted' => intval($this->isReposted),
        ];
    }
}
