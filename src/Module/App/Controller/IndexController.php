<?php

namespace ApiExport\Module\App\Controller;

use SMS\Core\Controller\AbstractController;
use ApiExport\Module\App\Model\Manager;
use ApiExport\Module\App\Model\DTO;
use ApiExport\Module\App\Model\Dal;

/**
 * Class IndexController.
 */
class IndexController extends AbstractController
{
    /**
     *
     */
    public function indexAction()
    {
        return $this->render();
    }
}
