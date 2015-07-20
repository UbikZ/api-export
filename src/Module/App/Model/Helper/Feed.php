<?php

namespace ApiExport\Module\App\Model\Helper;

use ApiExport\Module\App\Model\DTO\FeedItem as DTOFeedItem;
use ApiExport\Module\App\Model\DTO\Feed as DTOFeed;
use ApiExport\Module\App\Model\DTO\FeedType as DTOFeedType;


/**
 * Class Feed
 */
class Feed
{
    /**
     * @param array $dal
     * @return DTOFeed
     */
    public function getFeedFromDalToDTO(array $dal)
    {
        $feed = new DTOFeed();

        return $feed;
    }

    /**
     * @param array $dal
     * @return DTOFeedItem
     */
    public function getFeedItemFromDalToDTO(array $dal)
    {
        $feedItem = new DTOFeedItem();

        return $feedItem;
    }

    public function getFeedTypeFromDalToDTO(array $dal)
    {
        $feedType = new DTOFeedType();

        return $feedType;
    }
}