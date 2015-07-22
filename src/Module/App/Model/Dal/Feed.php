<?php

namespace ApiExport\Module\App\Model\Dal;

use ApiExport\Module\App\Model\DTO;

/**
 * Class Feed.
 */
class Feed extends AbstractDal
{
    const TABLE_NAME = 'feed';
    const FETCH = 1; // 0001

    /**
     * @param DTO\Filter\Feed $feedFilter
     * @param null     $lazyOptions
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public static function getBaseSelect($feedFilter, $lazyOptions = null)
    {
        $queryBuilder = self::getConn()->createQueryBuilder()
            ->select(self::alias(['f.id', 'f.label', 'f.url', 'f.type_id', 'f.update_date', 'f.bitfield']))
            ->from(self::TABLE_NAME, 'f');
        if ($lazyOptions & FeedType::FETCH) {
            $queryBuilder
                ->addSelect(self::alias(['ft.id', 'ft.label', 'ft.bitfield']))
                ->join('f', 'feed_type', 'ft', 'f.type_id = ft.id');
        }
        if ($id = $feedFilter->id) {
            $queryBuilder->andWhere('f.id = :id')->setParameter(':id', $id);
        }
        if ($label = $feedFilter->label) {
            $queryBuilder->andWhere('f.label = :label')->setParameter(':label', $label);
        }
        if ($typeId = $feedFilter->typeId) {
            $queryBuilder->andWhere('f.type_id = :type_id')->setParameter(':type_id', $typeId);
        }
        if ($bitField = $feedFilter->bitField) {
            $queryBuilder->andWhere('f.bitfield = :bitfield')->setParameter(':bitfield', $bitField);
        }
        if ($startDate = $feedFilter->startDate) {
            $queryBuilder
                ->andWhere('f.update_date >= :start_date')
                ->setParameter(':start_date', $startDate->format('Y-m-d'));
        }
        if ($endDate = $feedFilter->endDate) {
            $queryBuilder
                ->andWhere('f.update_date <= :end_date')
                ->setParameter(':end_date', $endDate->add(new \DateInterval('PT23H59M59S'))->format('Y-m-d H:i:s'));
        }

        return $queryBuilder;
    }
}
