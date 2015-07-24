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

        $results = Dal\FeedItem::get($feedItem, $lazyOptions);

        foreach ($results as $result) {
            $feedItem = Helper\Feed::getFeedItemFromDalToDTO($result);
            $feedItems[] = $toArray ? $feedItem->toArray() : $feedItem;
        }

        return $feedItems;
    }

    /**
     * @param DTO\FeedItem[] $feedItems
     */
    public static function insert(array $feedItems)
    {
        $return = Dal\FeedItem::insert($feedItems);
    }
}
