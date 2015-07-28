<?php

namespace ApiExport\Module\App\Model\DTO\Filter;

/**
 * Class FeedItem.
 */
class FeedItem
{
    /** @var  int */
    public $id;
    /** @var  int */
    public $feedId;
    /** @var  string */
    public $hash;
    /** @var  \DateTime */
    public $startDate;
    /** @var  \DateTime */
    public $endDate;
    /** @var  bool */
    public $isEnabled;
    /** @var  bool */
    public $isViewed;
    /** @var  bool */
    public $isApproved;
    /** @var  bool */
    public $isReposted;
}
