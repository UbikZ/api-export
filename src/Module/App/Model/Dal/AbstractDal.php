<?php

namespace ApiExport\Module\App\Model\Dal;

use Doctrine\DBAL\Query\QueryBuilder;
use SMS\Core\Exception\ErrorSQLStatementException;
use SMS\Core\Silex\Layout;

/**
 * Class Feed.
 */
abstract class AbstractDal implements InterfaceDal
{
    /**
     * @param bool $useDbal
     *
     * @return \Doctrine\DBAL\Connection|\Doctrine\DBAL\Driver\PDOConnection
     */
    public static function getConn($useDbal = true)
    {
        return Layout::database()->connect($useDbal);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return \Doctrine\DBAL\Driver\Statement|int|null
     * @throws ErrorSQLStatementException
     */
    public static function execute(QueryBuilder $queryBuilder)
    {
        $executed = null;
        try {
            $executed = $queryBuilder->execute();
            Layout::logger('sql_requests')->info(strtr(
                $queryBuilder->getSQL(),
                array_map(function($el) { return "'".$el."'"; }, $queryBuilder->getParameters()))
            );
        } catch (\PDOException $e) {
            throw new ErrorSQLStatementException("", 0, $e);
        }

        return $executed;
    }

    /**
     * @param array $params
     * @return array
     */
    public static function alias(array $params)
    {
        return array_map(function($el) { return sprintf('%s as %s', $el, str_replace('.', '_', $el)); }, $params);
    }

    /**
     * @param $filter
     * @param null $lazyOptions
     * @return array
     * @throws ErrorSQLStatementException
     */
    public static function get($filter, $lazyOptions = null)
    {
        return self::execute(static::getBaseSelect($filter, $lazyOptions))->fetchAll();
    }
}
