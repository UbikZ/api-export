<?php

namespace ApiExport\Module\App\Business\Feed\Parser;

use ApiExport\Module\App\Business\Feed\Parser;
use Guzzle\Http;

/**
 * Class Parser.
 */
abstract class AbstractParser implements InterfaceParser
{
    const URL = 'http:\/\/[0-9a-zA-Z\-_\./]+';
    const EXT = '/\.(%s)/i';

    /** @var string */
    protected $content = '';
    /** @var array  */
    protected $allowedExtensions = [];

    private $streamDomains = ['imgur'];

    public function __construct($content, $allowedExt = ['jpg', 'png', 'jpeg', 'gif', 'png', 'gifv'])
    {
        $this->content = $content;
        $this->allowedExtensions = $allowedExt;
    }

    /**
     * @param $type
     *
     * @return null|string|array
     */
    public function parse($type)
    {
        $result = [];

        if ($this->content !== null && !empty($this->content)) {
            $document = new \DOMDocument();
            $document->loadHTML($this->content);
            $aNodes = $document->getElementsByTagName('a');
            foreach ($aNodes as $aNode) {
                $link = $aNode->getAttribute('href');
                if (false === strpos($link, $type)) {
                    if (preg_match(sprintf(self::EXT, implode('|', $this->allowedExtensions)), $link)) {
                        $result[] = $link;
                    } else {
                        $streamed = false;
                        foreach ($this->streamDomains as $streamDomain) {
                            if (strpos($link, $streamDomain)) {
                                $parser = new Parser(null, $streamDomain, $link);
                                $result = array_merge($result, $parser->parse(null, true));
                                $streamed = true;
                                break;
                            }
                        }
                        if (!$streamed && !empty($link)) {
                            $result[] = $link;
                        }
                    }
                }
            }
        }
        
        return $result;
    }

    /**
     * @param $url
     * @param $realType
     *
     * @return mixed
     */
    abstract public function parseStream($url, $realType);

    /**
     * @param $url
     *
     * @return $this|Http\EntityBodyInterface
     */
    protected function streamContent($url)
    {
        return file_get_contents($url);
    }

    /*
     * Getters / Setters
     */

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return array
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    /**
     * @param array $allowedExtensions
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;
    }
}
