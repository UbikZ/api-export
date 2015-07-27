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
        if ($request->get('reposted', false)) {
            $bitField |= DTO\BitField::REPOSTED;
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
        $dtoItem->setBitField($this->getBitField($request, $dtoItem->getBitField()));

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
        $items = Manager\FeedItem::get(new DTO\Filter\FeedItem());
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
                $dtoFeedItem->setBitField(DTO\BitField::ENABLED);

                $parser = new Parser($dtoFeedItem->getResume(), $feed->getType()->getLabel());
                $extract = $parser->parse();
                $dtoFeedItem->setExtract(json_encode($extract));
                if (is_array($extract)) {
                    foreach ($extract as $key => $oneExtract) {
                        $dtoFeedItemCloned = clone $dtoFeedItem;
                        $dtoFeedItemCloned->setHash($dtoFeedItem->getHash().'_'.$key);
                        $dtoFeedItemCloned->setExtract($oneExtract);
                        if (!in_array($dtoFeedItemCloned->getHash(), $hashList)) {
                            $toBeInserted[] = $dtoFeedItemCloned;
                        }
                    }
                }
            }
        }

        Manager\FeedItem::insert($toBeInserted);

        return true;
    }
}
