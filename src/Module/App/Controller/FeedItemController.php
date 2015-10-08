<?php

namespace ApiExport\Module\App\Controller;

use ApiExport\Module\App\Business\Feed\Parser;
use PicoFeed\Parser\Item;
use PicoFeed\Reader\Reader;
use SMS\Core\Controller\AbstractController;
use ApiExport\Module\App\Model\Manager;
use ApiExport\Module\App\Model\DTO;
use ApiExport\Module\App\Model\Dal;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FeedItemController.
 */
class FeedItemController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAction(Request $request)
    {
        $filterFeed = new DTO\Filter\FeedItem();
        $filterFeed->id = $request->get('id');
        $filterFeed->isEnabled = $request->get('enabled');
        $filterFeed->isApproved = $request->get('approved');
        $filterFeed->isReposted = $request->get('reposted');
        $filterFeed->isViewed = $request->get('viewed');
        $filterFeed->startDate = $request->get('startDate') ? new \DateTime($request->get('startDate')) : null;
        $filterFeed->endDate = $request->get('endDate') ? new \DateTime($request->get('endDate')) : null;
        $feeds = Manager\FeedItem::get($filterFeed, null, true);

        return $this->sendJson($feeds);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getStatisticsItemAction(Request $request)
    {
        $limit = $request->query->get('limit', 5);
        $filterFeed = new DTO\Filter\FeedItem();
        $filterFeed->isEnabled = $request->query->get('enabled');
        $filterFeed->isApproved = $request->query->get('approved');
        $filterFeed->isReposted = $request->query->get('reposted');
        $filterFeed->isViewed = $request->query->get('viewed');
        $filterFeed->endDate = new \DateTime();

        $globalCount =  Manager\FeedItem::count('items', $filterFeed, $limit);
        $filterFeed->isApproved = true;
        $approvedCount = Manager\FeedItem::count('approved', $filterFeed, $limit);
        $filterFeed->isApproved = false;
        $filterFeed->isReposted = true;
        $repostCount = Manager\FeedItem::count('reposted', $filterFeed, $limit);


        $result = [];
        foreach ($globalCount as $date => $element) {
            $result[] = [
                'day' => $date,
                'items' => $element['items'],
                'approved' => isset($approvedCount[$date]['approved']) ? $approvedCount[$date]['approved'] : 0,
                'reposted' => isset($repostCount[$date]['reposted']) ? $repostCount[$date]['reposted'] : 0,
            ];
        }

        return $this->sendJson($result);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getStatisticsFeedAction(Request $request)
    {
        return $this->sendJson(Manager\FeedItem::countByFeed());
    }

    /**
     * @param Request $request
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function updateAction(Request $request)
    {
        $feedItemFilter = new DTO\Filter\FeedItem();
        $feedItemFilter->id = $request->get('id');
        $items = Manager\FeedItem::get($feedItemFilter);
        if (!$feedItemFilter->id || !isset($items[0])) {
            throw new \Exception('Feed Item `'.$feedItemFilter->id.'` not found.');
        }
        /** @var DTO\FeedItem $dtoItem */
        $dtoItem = $items[0];
        $dtoItem->setIsEnabled($request->get('enabled', $dtoItem->isEnabled()));
        $dtoItem->setIsApproved($request->get('approved', $dtoItem->isApproved()));
        $dtoItem->setIsViewed($request->get('viewed', $dtoItem->isViewed()));
        $dtoItem->setIsReposted($request->get('reposted', $dtoItem->isReposted()));
        $dtoItem->setComment($request->get('comment', $dtoItem->getComment()));

        Manager\FeedItem::update($dtoItem);

        return true;
    }

    /**
     * @return bool
     *
     * @throws \ApiExport\Module\App\Business\Feed\Exception\InvalidClassFeedParserException
     * @throws \PicoFeed\Parser\MalformedXmlException
     * @throws \PicoFeed\Reader\UnsupportedFeedFormatException
     */
    public function createAction()
    {
        $filterFeed = new DTO\Filter\FeedItem();
        $filterFeed->startDate = new \DateTime();
        $items = Manager\FeedItem::get($filterFeed);
        $hashList = array_map(function ($el) { return $el->getHash(); }, $items);
        $feeds = Manager\Feed::get(new DTO\Filter\Feed(), Dal\FeedType::FETCH);
        $reader = new Reader();

        /** @var DTO\FeedItem[] $toBeInserted */
        $toBeInserted = [];

        foreach ($feeds as $feed) {
            $resource = $reader->download($feed->getUrl());
            $parser = $reader->getParser($resource->getUrl(), $resource->getContent(), $resource->getEncoding());
            $feedUsed = $parser->execute();

            /** @var Item $item */
            foreach ($feedUsed->items as $item) {
                $dtoFeedItem = new DTO\FeedItem();
                $dtoFeedItem->setHash($item->getId());
                $dtoFeedItem->setTitle($item->getTitle());
                $dtoFeedItem->setUrl($item->getUrl());
                $dtoFeedItem->setResume($item->getContent());
                $dtoFeedItem->setFeed($feed);

                $parser = new Parser($dtoFeedItem->getResume(), $feed->getType()->getLabel());
                $extract = $parser->parse();
                $dtoFeedItem->setExtract(json_encode($extract));
                if (is_array($extract)) {
                    $count = count($extract);
                    foreach ($extract as $key => $oneExtract) {
                        if (!empty($oneExtract)) {
                            $dtoFeedItemCloned = clone $dtoFeedItem;
                            $dtoFeedItemCloned->setHash($dtoFeedItem->getHash());
                            $dtoFeedItemCloned->setOffset($count > 1 ? $key : null);
                            $dtoFeedItemCloned->setExtract($oneExtract);
                            if (!in_array($dtoFeedItemCloned->getHash(), $hashList)) {
                                $toBeInserted[] = $dtoFeedItemCloned;
                            }
                        }
                    }
                }
            }
        }

        Manager\FeedItem::insert($toBeInserted);

        return true;
    }
}
