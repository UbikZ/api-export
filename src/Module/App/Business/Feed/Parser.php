<?php

namespace ApiExport\Module\App\Business\Feed;

use ApiExport\Module\App\Business\Feed\Exception\InvalidClassFeedParserException;
use ApiExport\Module\App\Business\Feed\Exception\InvalidUrlFeedException;
use ApiExport\Module\App\Business\Feed\Parser\InterfaceParser;
use ApiExport\Module\App\Model\DTO;

/**
 * Class Parser.
 */
class Parser
{
    /** @var  string */
    protected $content;
    /** @var  string */
    protected $type;
    /** @var  string */
    protected $url;

    /**
     * @param $content
     * @param $type
     * @param null $url
     */
    public function __construct($content, $type, $url = null)
    {
        $this->content = $content;
        $this->type = $type;
        $this->url = $url;
    }

    /**
     * @param null $type
     * @param bool $streamContent
     * @return array|mixed|null
     * @throws InvalidClassFeedParserException
     */
    public function parse($type = null, $streamContent = false)
    {
        $realType = $type ?: $this->type;
        $className = __NAMESPACE__.'\\Parser\\'.ucfirst($realType);
        if (!class_exists($className)) {
            throw new InvalidClassFeedParserException();
        }

        /** @var InterfaceParser $class */
        $class = (new \ReflectionClass($className))->newInstance($this->content);

        return $streamContent ? $class->parseStream($this->url, $realType) : $class->parse($realType);
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}