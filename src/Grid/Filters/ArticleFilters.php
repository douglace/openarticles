<?php 

declare(strict_types=1);

namespace Vex6\OpenArticles\Grid\Filters;

use PrestaShop\PrestaShop\Core\Search\Filters;
use Vex6\OpenArticles\Grid\Definition\Factory\ArticleDefinitionFactory;

class ArticleFilters extends Filters
{
    protected $filterId = ArticleDefinitionFactory::GRID_ID;

    /**
     * {@inheritdoc}
     */
    public static function getDefaults()
    {
        return [
            'limit' => 10,
            'offset' => 0,
            'orderBy' => 'article_id',
            'sortOrder' => 'asc',
            'filters' => [],
        ];
    }
}