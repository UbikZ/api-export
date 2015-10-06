<?php

namespace ApiExport\Module\App\Model\Dal;

use ApiExport\Module\App\Model\DTO;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Class FeedItem.
 */
class FeedItem extends AbstractDal
{
    const TABLE_NAME = 'feed_item';
    const FETCH = 2; // 0010

    /**
     * @param QueryBuilder $queryBuilder
     * @param $feedItemFilter
     * @param null $lazyOptions
     */
    public static function parseFilter(QueryBuilder &$queryBuilder, $feedItemFilter, $lazyOptions = null)
    {
        if ($lazyOptions & Feed::FETCH) {
            $queryBuilder
                ->addSelect(self::alias(['f.id', 'f.label', 'f.url', 'f.update_date', 'f.is_enabled']))
                ->join('fi', 'feed', 'f', 'f.id = fi.feed_id');
            if ($lazyOptions & FeedType::FETCH) {
                $queryBuilder
                    ->addSelect(self::alias(['ft.id', 'ft.label', 'ft.is_enabled']))
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

        /** @var \DateTime $startDate */
        if ($startDate = $feedItemFilter->startDate) {
            $queryBuilder
                ->andWhere('fi.update_date >= :start_date')
                ->setParameter(':start_date', $startDate->format('Y-m-d'));
        }

        /** @var \DateTime $endDate */
        if ($endDate = $feedItemFilter->endDate) {
            $endDate->setTime(0, 0);
            $queryBuilder
                ->andWhere('fi.update_date <= :end_date')
                ->setParameter(':end_date', $endDate->add(new \DateInterval('PT23H59M59S'))->format('Y-m-d H:i:s'));
        }
    }

    /**
     * @param $feedItemFilter
     * @param null $lazyOptions
     * @return array
     * @throws \SMS\Core\Exception\ErrorSQLStatementException
     */
    public static function count($feedItemFilter, $lazyOptions = null)
    {
        $queryBuilder = self::getConn()->createQueryBuilder()
            ->select(['COUNT(id) as count', 'update_date'])
            ->from(self::TABLE_NAME, 'fi')
            ->orderBy('update_date');

        self::parseFilter($queryBuilder, $feedItemFilter, $lazyOptions);

        return self::execute($queryBuilder)->fetchAll()[0];
    }

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
                'fi.offset',
                'fi.update_date',
                'fi.resume',
                'fi.extract',
                'fi.is_enabled',
                'fi.is_viewed',
                'fi.is_approved',
                'fi.is_reposted',
            ]))
            ->from(self::TABLE_NAME, 'fi');
        self::parseFilter($queryBuilder, $feedItemFilter, $lazyOptions);

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
                    'offset' => ':offset',
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
                    ':offset' => $feedItem->getOffset(),
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
