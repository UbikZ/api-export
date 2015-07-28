<?php

namespace ApiExport\Module\App\Model\Dal;

use ApiExport\Module\App\Model\DTO;

/**
 * Class FeedItem.
 */
class FeedItem extends AbstractDal
{
    const TABLE_NAME = 'feed_item';
    const FETCH = 2; // 0010

    /**
     * @param DTO\Filter\FeedItem $feedItemFilter
     * @param null                $lazyOptions
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public static function getBaseSelect($feedItemFilter, $lazyOptions = null)
    {
        $queryBuilder = self::getConn()->createQueryBuilder()
            ->select(self::alias([
                'fi.id',
                'fi.feed_id',
                'fi.hash',
                'fi.update_date',
                'fi.resume',
                'fi.extract',
                'fi.is_enabled',
                'fi.is_viewed',
                'fi.is_approved',
                'fi.is_reposted',
            ]))
            ->from(self::TABLE_NAME, 'fi');
        if ($lazyOptions & Feed::FETCH) {
            $queryBuilder
                ->addSelect(self::alias(['f.id', 'f.label', 'f.url', 'f.update_date', 'f.bitfield']))
                ->join('fi', 'feed', 'f', 'f.id = fi.feed_id');
            if ($lazyOptions & FeedType::FETCH) {
                $queryBuilder
                    ->addSelect(self::alias(['ft.id', 'ft.label', 'ft.bitfield']))
                    ->join('f', 'feed_type', 'ft', 'f.type_id = ft.id');
            }
        }

        if ($id = $feedItemFilter->id) {
            $queryBuilder->andWhere('fi.id = :id')->setParameter(':id', $id);
        }
        if ($feedId = $feedItemFilter->feedId) {
            $queryBuilder->andWhere('fi.feed_id = :feed_id')->setParameter(':feed_id', $feedId);
        }
        if (!is_null($isEnabled = $feedItemFilter->isEnabled)) {
            $queryBuilder->andWhere('fi.is_enabled = :is_enabled')->setParameter(':is_enabled', intval($isEnabled));
        }
        if (!is_null($isViewed = $feedItemFilter->isViewed)) {
            $queryBuilder->andWhere('fi.is_viewed = :is_viewed')->setParameter(':is_viewed', intval($isViewed));
        }
        if (!is_null($isApproved = $feedItemFilter->isApproved)) {
            $queryBuilder->andWhere('fi.is_approved = :is_approved')->setParameter(':is_approved', intval($isApproved));
        }
        if (!is_null($isReposted = $feedItemFilter->isReposted)) {
            $queryBuilder->andWhere('fi.is_reposted = :is_reposted')->setParameter(':is_reposted', intval($isReposted));
        }
        if ($startDate = $feedItemFilter->startDate) {
            $queryBuilder
                ->andWhere('fi.update_date >= :start_date')
                ->setParameter(':start_date', $startDate->format('Y-m-d'));
        }
        if ($endDate = $feedItemFilter->endDate) {
            $queryBuilder
                ->andWhere('fi.update_date <= :end_date')
                ->setParameter(':end_date', $endDate->add(new \DateInterval('PT23H59M59S'))->format('Y-m-d H:i:s'));
        }

        return $queryBuilder;
    }

    /**
     * @param DTO\FeedItem[] $feedItems
     */
    public static function insert(array $feedItems)
    {
        $queryBuilder = self::getConn()->createQueryBuilder()->insert(self::TABLE_NAME);
        foreach ($feedItems as $feedItem) {
            $queryBuilder
                ->values([
                    'feed_id' => ':feed_id',
                    'hash' => ':hash',
                    'title' => ':title',
                    'categories' => ':categories',
                    'author_name' => ':author_name',
                    'author_uri' => ':author_uri',
                    'url' => ':url',
                    'update_date' => ':update_date',
                    'extract' => ':extract',
                    'is_enabled' => ':is_enabled',
                    'is_viewed' => ':is_viewed',
                    'is_approved' => ':is_approved',
                    'is_reposted' => ':is_reposted',
                ])
                ->setParameters([
                    ':feed_id' => $feedItem->getFeed()->getId(),
                    ':hash' => $feedItem->getHash(),
                    ':title' => $feedItem->getTitle(),
                    ':categories' => $feedItem->getCategories(),
                    ':author_name' => $feedItem->getAuthorName(),
                    ':author_uri' => $feedItem->getAuthorUri(),
                    ':url' => $feedItem->getUrl(),
                    ':update_date' => $feedItem->getUpdateDate(),
                    ':extract' => $feedItem->getExtract(),
                    ':is_enabled' => $feedItem->isEnabled(),
                    ':is_viewed' => $feedItem->isViewed(),
                    ':is_approved' => $feedItem->isApproved(),
                    ':is_reposted' => $feedItem->isReposted(),
                ]);
            self::execute($queryBuilder);
        }
    }

    /**
     * @param DTO\FeedItem $feedItem
     *
     * @throws \SMS\Core\Exception\ErrorSQLStatementException
     */
    public static function update(DTO\FeedItem $feedItem)
    {
        $queryBuilder = self::getConn()->createQueryBuilder()
            ->update(self::TABLE_NAME)
            ->set('is_enabled', ':is_enabled')
            ->set('is_viewed', ':is_viewed')
            ->set('is_approved', ':is_approved')
            ->set('is_reposted', ':is_reposted')
            ->where('id = :id')
            ->setParameters([
                ':is_enabled' => $feedItem->isEnabled(),
                ':is_viewed' => $feedItem->isViewed(),
                ':is_approved' => $feedItem->isApproved(),
                ':is_reposted' => $feedItem->isReposted(),
                ':id' => $feedItem->getId(),
            ]);

        self::execute($queryBuilder);
    }
}
