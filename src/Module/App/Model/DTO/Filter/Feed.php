<?php

namespace ApiExport\Module\App\Model\DTO\Filter;

/**
 * Class Feed.
 */
class Feed
{
    /** @var  int */
    public $id;
    /** @var  int */
    public $typeId;
    /** @var  string */
    public $label;
    /** @var  \DateTime */
    public $startDate;
    /** @var  \DateTime */
    public $endDate;
    /** @var  bool */
    public $isEnabled;
}
