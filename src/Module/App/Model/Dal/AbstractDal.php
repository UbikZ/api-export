<?php

namespace ApiExport\Module\App\Model\Dal;

use ApiExport\Module\App\Model\DTO;
use SMS\Core\Silex\Layout;

/**
 * Class Feed.
 */
abstract class AbstractDal
{
    /**
     * @param bool $useDbal
     * @return \Doctrine\DBAL\Connection|\Doctrine\DBAL\Driver\PDOConnection
     */
    public static function getConn($useDbal = true)
    {
        return Layout::database()->connect($useDbal);
    }
}
