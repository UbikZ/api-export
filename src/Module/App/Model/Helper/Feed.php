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
        $feed->setId(self::get($dal, 'f_id'));
        $feed->setLabel(self::get($dal, 'f_label'));
        $feed->setUrl(self::get($dal, 'f_url'));

        $feedType = new DTO\FeedType();
        $feedType->setId(self::get($dal, 'f_type_id'));
        if (self::get($dal, 'ft_id')) {
            $feedType = self::getFeedTypeFromDalToDTO($dal);
        }
        $feed->setType($feedType);
        $feed->setUpdateDate(self::get($dal, 'f_update_date'));
        $feed->setBitField(self::get($dal, 'f_bitfield'));

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
        $feedItem->setId(self::get($dal, 'fi_id'));

        $feed = new DTO\Feed();
        $feed->setId(self::get($dal, 'fi_feed_id'));
        if (self::get($dal, 'f_id')) {
            $feed = self::getFeedFromDalToDTO($dal);
        }
        $feedItem->setFeed($feed);
        $feedItem->setHash(self::get($dal, 'fi_hash'));
        $feedItem->setTitle(self::get($dal, 'fi_title'));
        $feedItem->setCategories(self::get($dal, 'fi_categories'));
        $feedItem->setAuthorName(self::get($dal, 'fi_author_name'));
        $feedItem->setAuthorUri(self::get($dal, 'fi_author_uri'));
        $feedItem->setUrl(self::get($dal, 'fi_url'));
        $feedItem->setUpdateDate(self::get($dal, 'fi_update_date'));
        $feedItem->setResume(self::get($dal, 'fi_resume'));
        $feedItem->setExtract(self::get($dal, 'fi_extract'));
        $feedItem->setBitField(self::get($dal, 'fi_bitfield'));

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
        $feedType->setId(self::get($dal, 'ft_id'));
        $feedType->setLabel(self::get($dal, 'ft_label'));
        $feedType->setBitField(self::get($dal, 'ft_bitfield'));

        return $feedType;
    }

    /**
     * @param $elements
     * @param $key
     */
    private function get($elements, $key)
    {
        return isset($elements[$key]) ? $elements[$key] : null;
    }
}
