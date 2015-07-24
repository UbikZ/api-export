<?php

namespace ApiExport\Module\App\Business\Feed\Parser;

/**
 * Interface InterfaceParser.
 */
interface InterfaceParser
{
    /**
     * @param $type
     * @return mixed|null|array
     */
    public function parse($type);

    /**
     * @param $url
     * @param $realType
     * @return mixed
     */
    public function parseStream($url, $realType);
}