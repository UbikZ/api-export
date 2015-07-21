<?php

namespace ApiExport\Module\App\Model\Dal;

use ApiExport\Module\App\Model\DTO;
use SMS\Core\Exception\ErrorSQLStatementException;

/**
 * Class Feed.
 */
class Feed extends AbstractDal
{
    const TABLE_NAME = 'feed';
    const TABLE_BIT = 1; // 0001

    /**
     * @param DTO\Feed $feed
     * @param null     $lazyOptions
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    private static function getBaseSelect(DTO\Feed $feed, $lazyOptions = null)
    {
        $queryBuilder = self::getConn()->createQueryBuilder()
            ->select('f.*')
            ->from(self::TABLE_NAME, 'f');
        if ($lazyOptions & FeedType::TABLE_BIT) {
            $queryBuilder
                ->addSelect(['ft.id as ft_id', 'ft.label as ft_label', 'ft.bitfield as ft_bitfield'])
                ->join('f', 'feed_type', 'ft', 'f.type_id = ft.id');
        }
        if ($id = $feed->getId()) {
            $queryBuilder->andWhere('f.id = :id')->setParameter(':id', $id);
        }
        if ($label = $feed->getLabel()) {
            $queryBuilder->andWhere('f.label = :label')->setParameter(':label', $label);
        }
        if ($feed->getType() && ($typeId = $feed->getType()->getId())) {
            $queryBuilder->andWhere('f.type_id = :type_id')->setParameter(':type_id', $typeId);
        }
        if (!is_null($bitField = $feed->getBitField())) {
            $queryBuilder->andWhere('f.bitfield = :bitfield')->setParameter(':bitfield', $bitField);
        }

        return $queryBuilder;
    }

    /**
     * @param DTO\Feed $feed
     * @param null     $lazyOptions
     *
     * @return array
     *
     * @throws ErrorSQLStatementException
     */
    public static function get(DTO\Feed $feed, $lazyOptions = null)
    {
        if (!($executed = self::getBaseSelect($feed, $lazyOptions)->execute())) {
            throw new ErrorSQLStatementException('DAL\Feed::get() error');
        }

        return $executed->fetchAll();
    }
}
