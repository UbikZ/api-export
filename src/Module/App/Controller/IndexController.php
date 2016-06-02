<?php

namespace ApiExport\Module\App\Controller;

use ApiExport\Module\App\Model\DTO;
use ApiExport\Module\App\Model\Manager;
use SMS\Core\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class IndexController.
 */
class IndexController extends AbstractController
{
    private function _parseFilter(Request $request)
    {
        $filterFeed = new DTO\Filter\FeedItem();
        $filterFeed->id = $request->get('id');
        $filterFeed->isEnabled = $request->get('enabled');
        $filterFeed->isApproved = $request->get('approved');
        $filterFeed->isReposted = $request->get('reposted');
        $filterFeed->isViewed = $request->get('viewed');
        $filterFeed->startDate = $request->get('startDate') ? new \DateTime($request->get('startDate')) : new \DateTime();
        $filterFeed->endDate = $request->get('endDate') ? new \DateTime($request->get('endDate')) : null;

        return $filterFeed;
    }

    /**
     *
     */
    public function indexAction()
    {
        return $this->render();
    }

    public function viewAction(Request $request)
    {
        $filterFeed = $this->_parseFilter($request);
        $feeds = Manager\FeedItem::get($filterFeed, null, true);

        return $this->render(['feeds' => $feeds, 'links' => (bool) $request->get('links', false)]);
    }

    public function openAction(Request $request) {
        $filterFeed = $this->_parseFilter($request);
        $feeds = Manager\FeedItem::get($filterFeed, null, true);
        $urlFeeds = array_map(function($object) {
            /** @var DTO\FeedItem $object */
            return $object['url'];
        }, $feeds);

        return $this->render(['urls' => json_encode($urlFeeds)]);
    }
}
