<?php

namespace ApiExport\Module\App\Model\Dal;

use ApiExport\Module\App\Model\DTO;

/**
 * Class FeedItem.
 */
class FeedItem extends AbstractDal
{
    const TABLE_NAME = 'feed_item';
    const FETCH = 2; // 0010

    /**
     * @param DTO\Filter\FeedItem $feedItemFilter
     * @param null $lazyOptions
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public static function getBaseSelect($feedItemFilter, $lazyOptions = null)
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

        if ($id = $feedItemFilter->id) {
            $queryBuilder->andWhere('fi.id = :id')->setParameter(':id', $id);
        }
        if ($feedId = $feedItemFilter->feedId) {
            $queryBuilder->andWhere('fi.feed_id = :feed_id')->setParameter(':feed_id', $feedId);
        }
        if ($bitField = $feedItemFilter->bitField) {
            $queryBuilder->andWhere('fi.bitfield = :bitfield')->setParameter(':bitfield', $bitField);
        }
        if ($startDate = $feedItemFilter->startDate) {
            $queryBuilder
                ->andWhere('fi.update_date >= :start_date')
                ->setParameter(':start_date', $startDate->format('Y-m-d'));
        }
        if ($endDate = $feedItemFilter->endDate) {
            $queryBuilder
                ->andWhere('fi.update_date <= :end_date')
                ->setParameter(':end_date', $endDate->add(new \DateInterval('PT23H59M59S'))->format('Y-m-d H:i:s'));
        }

        return $queryBuilder;
    }
}
