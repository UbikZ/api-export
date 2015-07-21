<?php

namespace ApiExport\Module\App\Model\Manager;

use ApiExport\Module\App\Model\DTO;
use ApiExport\Module\App\Model\Dal;
use ApiExport\Module\App\Model\Helper;

/**
 * Class Feed.
 */
class Feed
{
    /**
     * @param DTO\Feed $feed
     * @param null $lazyOptions
     * @return DTO\Feed[]
     * @throws \SMS\Core\Exception\ErrorSQLStatementException
     */
    public static function get(DTO\Feed $feed, $lazyOptions = null)
    {
        /** @var DTO\Feed[] $feeds */
        $feeds = [];

        $results = Dal\Feed::get($feed, $lazyOptions);

        foreach ($results as $result) {
            $feeds[] = Helper\Feed::getFeedFromDalToDTO($result);
        }

        return $feeds;
    }
}
