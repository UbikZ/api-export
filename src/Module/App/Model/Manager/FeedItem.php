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
