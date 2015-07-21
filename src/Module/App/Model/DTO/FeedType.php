<?php

namespace ApiExport\Module\App\Model\DTO;

/**
 * Class FeedType.
 */
class FeedType
{
    /** @var  int */
    private $id;
    /** @var  string */
    private $label;
    /** @var  int */
    private $bitField;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = intval($id);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return int
     */
    public function getBitField()
    {
        return $this->bitField;
    }

    /**
     * @param int $bitField
     */
    public function setBitField($bitField)
    {
        $this->bitField = intval($bitField);
    }
}
