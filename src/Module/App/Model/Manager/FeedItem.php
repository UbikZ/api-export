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
     * @param DTO\FeedItem $feedItem
     * @param null $lazyOptions
     * @return DTO\Feed[]
     * @throws \SMS\Core\Exception\ErrorSQLStatementException
     */
    public static function get(DTO\FeedItem $feedItem, $lazyOptions = null)
    {
        /** @var DTO\Feed[] $feeds */
        $feeds = [];

        $results = Dal\FeedItem::get($feedItem, $lazyOptions);

        foreach ($results as $result) {
            $feeds[] = Helper\Feed::getFeedItemFromDalToDTO($result);
        }

        return $feeds;
    }
}
