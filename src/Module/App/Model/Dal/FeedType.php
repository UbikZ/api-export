<?php

namespace ApiExport\Module\App\Model\Dal;

use ApiExport\Module\App\Model\DTO;
use SMS\Core\Exception\ErrorSQLStatementException;

/**
 * Class FeedType.
 */
class FeedType extends AbstractDal
{
    const TABLE_NAME = 'feed_type';
    const FETCH = 4; // 0100

    /**
     * @param DTO\FeedType $feedType
     * @param null $lazyOptions
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    private static function getBaseSelect(DTO\FeedType $feedType, $lazyOptions = null)
    {
        $queryBuilder = self::getConn()->createQueryBuilder()
            ->select('ft.*')
            ->from(self::TABLE_NAME, 'ft');
        if ($id = $feedType->getId()) {
            $queryBuilder->andWhere('ft.id = :id')->setParameter(':id', $id);
        }
        if ($label = $feedType->getLabel()) {
            $queryBuilder->andWhere('ft.label = :label')->setParameter(':label', $label);
        }
        if (!is_null($bitField = $feedType->getBitField())) {
            $queryBuilder->andWhere('ft.bitfield = :bitfield')->setParameter(':bitfield', $bitField);
        }

        return $queryBuilder;
    }

    /**
     * @param DTO\FeedType $feedType
     * @param null $lazyOptions
     * @return array
     * @throws ErrorSQLStatementException
     */
    public static function get(DTO\FeedType $feedType, $lazyOptions = null)
    {
        if (!($executed = self::getBaseSelect($feedType, $lazyOptions)->execute())) {
            throw new ErrorSQLStatementException('DAL\FeedType::get() error');
        }

        return $executed->fetchAll();
    }
}
