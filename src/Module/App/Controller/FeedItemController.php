<?php

namespace ApiExport\Module\App\Controller;

use SMS\Core\Controller\AbstractController;
use ApiExport\Module\App\Model\Manager;
use ApiExport\Module\App\Model\DTO;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FeedItemController.
 */
class FeedItemController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return int|null
     */
    private function getBitField(Request $request)
    {
        $bitField = null;
        if ($request->get('enabled', false)) {
            $bitField |= DTO\BitField::ENABLED;
        }
        if ($request->get('viewed', false)) {
            $bitField |= DTO\BitField::VIEWED;
        }
        if ($request->get('approved', false)) {
            $bitField |= DTO\BitField::APPROVED;
        }

        return $bitField;
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAction(Request $request)
    {
        $filterFeed = new DTO\Filter\FeedItem();
        $filterFeed->id = $request->get('id');
        $filterFeed->startDate = $request->get('startDate') ? new \DateTime($request->get('startDate')) : null;
        $filterFeed->endDate = $request->get('endDate') ? new \DateTime($request->get('endDate')) : null;
        $filterFeed->bitField = $this->getBitField($request);
        $feeds = Manager\FeedItem::get($filterFeed, null, true);

        return $this->sendJson($feeds);
    }
}
