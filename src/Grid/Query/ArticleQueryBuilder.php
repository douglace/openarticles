<?php 

declare(strict_types=1);

namespace Vex6\OpenArticles\Grid\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Core\Grid\Query\Filter\SqlFilters;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\Filter\DoctrineFilterApplicatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicatorInterface;

class ArticleQueryBuilder extends AbstractDoctrineQueryBuilder
{

    /**
     * @var DoctrineSearchCriteriaApplicatorInterface
     */
    private $searchCriteriaApplicator;

    /**
     * @var int
     */
    private $contextLanguageId;


    /**
     * @var DoctrineFilterApplicatorInterface
     */
    private $filterApplicator;


    public function __construct(
        Connection $connection,
        string $dbPrefix,
        DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator,
        int $contextLanguageId,
        DoctrineFilterApplicatorInterface $filterApplicator
    ) {
        parent::__construct($connection, $dbPrefix);
        $this->searchCriteriaApplicator = $searchCriteriaApplicator;
        $this->contextLanguageId = $contextLanguageId;
        $this->filterApplicator = $filterApplicator;
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters());
        $qb
            ->select('oa.`id` article_id, oa.`position`, oa.`active`, 1 logo')
            ->addSelect('oal.`lang_id`, oal.`title`')
            ->addSelect('pl.`name` product')
        ;

        $this->searchCriteriaApplicator
            ->applyPagination($searchCriteria, $qb)
            ->applySorting($searchCriteria, $qb)
        ;

        return $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters());
        $qb->select('COUNT(oa.`id`)');

        return $qb;
    }

    /**
     * Gets query builder.
     *
     * @param array $filterValues
     *
     * @return QueryBuilder
     */
    private function getQueryBuilder(array $filterValues): QueryBuilder
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix . 'open_articles', 'oa')
            ->leftJoin(
                'oa',
                $this->dbPrefix . 'open_articles_lang',
                'oal',
                'oal.`open_article_id` = oa.`id` AND oal.`lang_id` = :lang_id'
            )->leftJoin(
                'oa',
                $this->dbPrefix . 'product_lang',
                'pl',
                'pl.`id_product` = oa.`product_id` AND pl.`id_lang` = :lang_id'
            )
        ;

        $sqlFilters = new SqlFilters();
        $sqlFilters
            ->addFilter(
                'id',
                'oa.`id`',
                SqlFilters::WHERE_STRICT
            );
        
        $this->filterApplicator->apply($qb, $sqlFilters, $filterValues);


        $qb->setParameter('lang_id', $this->contextLanguageId);

        
        foreach ($filterValues as $filterName => $filter) {
            if ('active' === $filterName) {
                $qb->andWhere('oa.`active` = :active');
                $qb->setParameter('active', $filter);

                continue;
            }

            if ('title' === $filterName) {
                $qb->andWhere('oal.`title` LIKE :title');
                $qb->setParameter('title', '%' . $filter . '%');

                continue;
            }

            

            if ('position' === $filterName) {
                $qb->andWhere('oa.`position` LIKE :position');
                $qb->setParameter('position', '%' . $filter . '%');

                continue;
            }

        }

        return $qb;
    }
}