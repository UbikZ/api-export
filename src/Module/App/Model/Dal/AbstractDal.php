<?php

namespace ApiExport\Module\App\Model\Dal;

use SMS\Core\Silex\Layout;

/**
 * Class Feed.
 */
abstract class AbstractDal
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
     * @param array $params
     * @return array
     */
    public static function alias(array $params)
    {
        return array_map(function($el) { return sprintf('%s as %s', $el, str_replace('.', '_', $el)); }, $params);
    }
}
