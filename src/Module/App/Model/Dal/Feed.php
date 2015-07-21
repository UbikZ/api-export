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
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    private static function getBaseSelect(DTO\Feed $feed)
    {
        $queryBuilder = self::getConn()->createQueryBuilder()
            ->select('*')
            ->from('feed');
        if ($id = $feed->getId()) {
            $queryBuilder->andWhere('id = :id')->setParameter(':id', $id);
        }
        if ($label = $feed->getLabel()) {
            $queryBuilder->andWhere('label = :label')->setParameter(':label', $label);
        }
        if ($feed->getType() && ($typeId = $feed->getType()->getId())) {
            $queryBuilder->andWhere('type_id = :type_id')->setParameter(':type_id', $typeId);
        }
        if (!is_null($isEnabled = $feed->isEnabled())) {
            $queryBuilder->andWhere('is_enabled = :is_enabled')->setParameter(':is_enabled', $isEnabled);
        }

        return $queryBuilder;
    }

    /**
     * @param DTO\Feed $feed
     * @return array
     * @throws ErrorSQLStatementException
     */
    public static function get(DTO\Feed $feed)
    {
        if (!($executed = self::getBaseSelect($feed)->execute())) {
            throw new ErrorSQLStatementException('DAL\Feed::get() error');
        }

        return $executed->fetchAll();
    }
}
