<?php

namespace ApiExport\Module\App\Model\Dal;

use ApiExport\Module\App\Model\DTO;
use SMS\Core\Exception\ErrorSQLStatementException;

/**
 * Class FeedItem.
 */
class FeedItem extends AbstractDal
{
    const TABLE_NAME = 'feed_item';
    const FETCH = 2; // 0010

    /**
     * @param DTO\FeedItem $feedItem
     * @param null $lazyOptions
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    private static function getBaseSelect(DTO\FeedItem $feedItem, $lazyOptions = null)
    {
        $queryBuilder = self::getConn()->createQueryBuilder()
            ->select(self::alias(['fi.id', 'fi.feed_id', 'fi.hash', 'fi.update_date', 'fi.resume', 'fi.bitfield']))
            ->from(self::TABLE_NAME, 'fi');
        if ($lazyOptions & Feed::FETCH) {
            $queryBuilder
                ->addSelect(self::alias(['f.id', 'f.label', 'f.url', 'f.update_date', 'f.bitfield']))
                ->join('fi', 'feed', 'f', 'f.id = fi.feed_id');
            if ($lazyOptions & FeedType::FETCH) {
                $queryBuilder
                    ->addSelect(self::alias(['ft.id', 'ft.label', 'ft.bitfield']))
                    ->join('f', 'feed_type', 'ft', 'f.type_id = ft.id');
            }
        }

        if ($id = $feedItem->getId()) {
            $queryBuilder->andWhere('fi.id = :id')->setParameter(':id', $id);
        }
        if ($feedItem->getFeed() && ($label = $feedItem->getFeed()->getId())) {
            $queryBuilder->andWhere('fi.feed_id = :feed_id')->setParameter(':feed_id', $label);
        }

        if (!is_null($bitField = $feedItem->getBitField())) {
            $queryBuilder->andWhere('fi.bitfield = :bitfield')->setParameter(':bitfield', $bitField);
        }

        return $queryBuilder;
    }

    /**
     * @param DTO\FeedItem $feedItem
     * @param null $lazyOptions
     * @return array
     * @throws ErrorSQLStatementException
     */
    public static function get(DTO\FeedItem $feedItem, $lazyOptions = null)
    {
        if (!($executed = self::getBaseSelect($feedItem, $lazyOptions)->execute())) {
            throw new ErrorSQLStatementException('DAL\Feed::get() error');
        }

        return $executed->fetchAll();
    }
}
