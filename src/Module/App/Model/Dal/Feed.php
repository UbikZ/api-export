<?php

namespace ApiExport\Module\App\Model\Dal;

use ApiExport\Module\App\Model\DTO;
use SMS\Core\Exception\ErrorSQLStatementException;

/**
 * Class Feed.
 */
class Feed extends AbstractDal
{
    /**
     * @param DTO\Feed $feed
     * @param bool     $lazy
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    private static function getBaseSelect(DTO\Feed $feed, $lazy = false)
    {
        $queryBuilder = self::getConn()->createQueryBuilder()
            ->select('f.*')
            ->from('feed', 'f');
        if (!$lazy) {
            $queryBuilder
                ->addSelect(['ft.id as ft_id', 'ft.label as ft_label', 'ft.is_enabled as ft_is_enabled'])
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
        if (!is_null($isEnabled = $feed->isEnabled())) {
            $queryBuilder->andWhere('f.is_enabled = :is_enabled')->setParameter(':is_enabled', $isEnabled);
        }

        return $queryBuilder;
    }

    /**
     * @param DTO\Feed $feed
     * @param bool     $lazy
     *
     * @return array
     *
     * @throws ErrorSQLStatementException
     */
    public static function get(DTO\Feed $feed, $lazy = true)
    {
        if (!($executed = self::getBaseSelect($feed, $lazy)->execute())) {
            throw new ErrorSQLStatementException('DAL\Feed::get() error');
        }

        return $executed->fetchAll();
    }
}
