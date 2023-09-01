<?php 

declare(strict_types=1);

namespace Vex6\OpenArticles\Grid\Data\Factory;

use PrestaShop\PrestaShop\Core\Grid\Data\GridData;
use PrestaShop\PrestaShop\Core\Grid\Record\RecordCollection;
use PrestaShop\PrestaShop\Core\Image\ImageProviderInterface;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;
use PrestaShop\PrestaShop\Core\Grid\Data\Factory\GridDataFactoryInterface;

/**
 * Gets data
 */
final class ArticleGridDataFactory implements GridDataFactoryInterface
{
    /**
     * @var GridDataFactoryInterface
     */
    private $gridDataFactory;


    /**
     * @var ImageProviderInterface
     */
    private $menLogoThumbnailProvider;

    /**
     * @param GridDataFactoryInterface $gridDataFactory
     * @param ImageProviderInterface $menLogoThumbnailProvider
     */
    public function __construct(
        GridDataFactoryInterface $gridDataFactory,
        ImageProviderInterface $menLogoThumbnailProvider
    ) {
        $this->gridDataFactory = $gridDataFactory;
        $this->menLogoThumbnailProvider = $menLogoThumbnailProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(SearchCriteriaInterface $searchCriteria)
    {
        $menData = $this->gridDataFactory->getData($searchCriteria);

        $modifiedRecords = $this->applyModification(
            $menData->getRecords()->all()
        );

        return new GridData(
            new RecordCollection($modifiedRecords),
            $menData->getRecordsTotal(),
            $menData->getQuery()
        );
    }

    /**
     * @param array $mens
     *
     * @return array
     */
    private function applyModification(array $rows)
    {
        foreach ($rows as &$row) {
            $row['logo'] = $this->menLogoThumbnailProvider->getPath(
                $row['article_id']
            );
        }

        return $rows;
    }
}