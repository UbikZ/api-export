<?php

namespace ApiExport\Module\App\Controller;

use SMS\Core\Controller\AbstractController;
use ApiExport\Module\App\Model\Manager;
use ApiExport\Module\App\Model\DTO;

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
        $filterFeed = new DTO\Feed();
        $feeds = Manager\Feed::get($filterFeed);
        dump($feeds);
        die;

        return $this->render();
    }
}
