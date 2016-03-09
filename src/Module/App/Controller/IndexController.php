<?php

namespace ApiExport\Module\App\Controller;

use SMS\Core\Controller\AbstractController;
use ApiExport\Module\App\Model\DTO;
use ApiExport\Module\App\Model\Manager;

use Symfony\Component\HttpFoundation\Request;

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

    public function viewAction(Request $request)
    {
      $filterFeed = new DTO\Filter\FeedItem();
      $filterFeed->id = $request->get('id');
      $filterFeed->isEnabled = $request->get('enabled');
      $filterFeed->isApproved = $request->get('approved');
      $filterFeed->isReposted = $request->get('reposted');
      $filterFeed->isViewed = $request->get('viewed');
      $filterFeed->startDate = new \DateTime();
      $feeds = Manager\FeedItem::get($filterFeed, null, true);

      return $this->render(['feeds' => $feeds]);
    }
}
