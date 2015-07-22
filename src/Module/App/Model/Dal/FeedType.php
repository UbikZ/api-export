<?php

namespace ApiExport\Module\App\Model\Dal;

use ApiExport\Module\App\Model\DTO;

/**
 * Class FeedType.
 */
class FeedType extends AbstractDal
{
    const TABLE_NAME = 'feed_type';
    const FETCH = 4; // 0100

    /**
     * @param DTO\Filter\FeedType $feedTypeFilter
     * @param null                $lazyOptions
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public static function getBaseSelect($feedTypeFilter, $lazyOptions = null)
    {
        $queryBuilder = self::getConn()->createQueryBuilder()
            ->select('ft.*')
            ->from(self::TABLE_NAME, 'ft');
        if ($id = $feedTypeFilter->id) {
            $queryBuilder->andWhere('ft.id = :id')->setParameter(':id', $id);
        }
        if ($label = $feedTypeFilter->label) {
            $queryBuilder->andWhere('ft.label = :label')->setParameter(':label', $label);
        }
        if ($bitField = $feedTypeFilter->bitField) {
            $queryBuilder->andWhere('ft.bitfield = :bitfield')->setParameter(':bitfield', $bitField);
        }

        return $queryBuilder;
    }
}
