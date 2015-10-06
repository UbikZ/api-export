<?php

namespace ApiExport\Module\App\Model\Manager;

use ApiExport\Module\App\Model\DTO;
use ApiExport\Module\App\Model\Dal;
use ApiExport\Module\App\Model\Helper;

/**
 * Class FeedItem.
 */
class FeedItem
{
    /**
     * @param DTO\Filter\FeedItem $feedItem
     * @param null                $lazyOptions
     * @param bool                $toArray
     *
     * @return DTO\FeedItem[]
     *
     * @throws \SMS\Core\Exception\ErrorSQLStatementException
     */
    public static function get(DTO\Filter\FeedItem $feedItem, $lazyOptions = null, $toArray = false)
    {
        /* @var DTO\FeedItem[] $feeds */
        $feedItems = [];

        $results = Dal\FeedItem::getStmt($feedItem, $lazyOptions);

        while ($row = $results->fetch()) {
            $feedItem = Helper\Feed::getFeedItemFromDalToDTO($row);
            $feedItems[] = $toArray ? $feedItem->toArray() : $feedItem;
        }

        return $feedItems;
    }

    /**
     * @param $key
     * @param DTO\Filter\FeedItem $feedItem
     * @param $limit
     * @param null $lazyOptions
     * @return array
     */
    public static function count($key, DTO\Filter\FeedItem $feedItem, $limit, $lazyOptions = null)
    {
        $results = [];
        for ($i = 0 ; $i < $limit ; $i++) {
            $subNow = new \DateTime();
            $subNow->sub(new \DateInterval(sprintf('P%dD', $i)));
            $feedItem->startDate = $feedItem->endDate = $subNow;
            $item =  Helper\Feed::countFeedItemFromDalToArray(
                $key,
                Dal\FeedItem::count($feedItem, $lazyOptions)
            );
            if ($item) {
                $results[$item['day']] = $item;
            }
        }

        return $results;
    }

    /**
     * @return array
     */
    public static function countByFeed()
    {
        return Helper\Feed::countByFeedFromDalToArray(Dal\FeedItem::countByFeed());
    }

    /**
     * @param DTO\FeedItem[] $feedItems
     */
    public static function insert(array $feedItems)
    {
        Dal\FeedItem::insert($feedItems);
    }

    /**
     * @param DTO\FeedItem $feedItem
     */
    public static function update($feedItem)
    {
        Dal\FeedItem::update($feedItem);
    }
}
