<?php

namespace ApiExport\Module\App\Model\Dal;

interface InterfaceDal
{
    /**
     * @param $filter
     * @param null $lazyOptions
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public static function getBaseSelect($filter, $lazyOptions = null);
}
