<?php

namespace ApiExport\Module\App\Model\Helper;

use ApiExport\Module\App\Model\DTO;

/**
 * Class Feed.
 */
class Feed
{
    /**
     * @param array $dal
     *
     * @return DTO\Feed
     */
    public static function getFeedFromDalToDTO(array $dal)
    {
        $feed = new DTO\Feed();
        $feed->setId($dal['id']);
        $feed->setLabel($dal['label']);
        $feed->setUrl($dal['url']);

        $feedType = new DTO\FeedType();
        $feedType->setId($dal['type_id']);
        if (isset($dal['ft_id'])) {
            $feedType->setLabel($dal['ft_label']);
            $feedType->setEnabled($dal['ft_is_enabled']);
        }
        $feed->setType($feedType);
        $feed->setUpdateDate($dal['update_date']);
        $feed->setEnabled($dal['is_enabled']);

        return $feed;
    }

    /**
     * @param array $dal
     *
     * @return DTO\FeedItem
     */
    public static function getFeedItemFromDalToDTO(array $dal)
    {
        $feedItem = new DTO\FeedItem();
        $feedItem->setId(intval($dal['id']));

        $feed = new DTO\Feed();
        $feed->setId($dal['feed_id']);
        $feedItem->setFeed($feed);
        $feedItem->setHash($dal['hash']);
        $feedItem->setTitle($dal['title']);
        $feedItem->setCategories($dal['categories']);
        $feedItem->setAuthorName($dal['author_name']);
        $feedItem->setAuthorUri($dal['author_uri']);
        $feedItem->setUrl($dal['url']);
        $feedItem->setUpdateDate(new \DateTime($dal['update_date']));
        $feedItem->setResume($dal['resume']);
        $feedItem->setExtract($dal['extract']);
        $feedItem->setEnabled($dal['is_enabled']);
        $feedItem->setViewed($dal['is_viewed']);
        $feedItem->setApproved($dal['is_approved']);

        return $feedItem;
    }

    /**
     * @param array $dal
     *
     * @return DTO\FeedType
     */
    public static function getFeedTypeFromDalToDTO(array $dal)
    {
        $feedType = new DTO\FeedType();
        $feedType->setId($dal['id']);
        $feedType->setLabel($dal['label']);
        $feedType->setEnabled($dal['is_enabled']);

        return $feedType;
    }
}
