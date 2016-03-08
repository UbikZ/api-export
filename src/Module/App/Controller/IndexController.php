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
      if (!$request->get('startDate')) {
        throw new Exception("Use 'startDate' parameter to scale your search.");
      }
      $filterFeed = new DTO\Filter\FeedItem();
      $filterFeed->id = $request->get('id');
      $filterFeed->isEnabled = $request->get('enabled');
      $filterFeed->isApproved = $request->get('approved');
      $filterFeed->isReposted = $request->get('reposted');
      $filterFeed->isViewed = $request->get('viewed');
      $filterFeed->startDate = $request->get('startDate') ? new \DateTime($request->get('startDate')) : null;
      $filterFeed->endDate = $request->get('endDate') ? new \DateTime($request->get('endDate')) : null;
      $feeds = Manager\FeedItem::get($filterFeed, null, true);

      return $this->render(['feeds' => $feeds]);
    }
}
