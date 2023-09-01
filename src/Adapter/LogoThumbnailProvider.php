<?php

namespace Vex6\OpenArticles\Adapter;

use HelperList;
use ImageManager;
use PrestaShop\PrestaShop\Core\Image\ImageProviderInterface;
use PrestaShop\PrestaShop\Core\Image\Parser\ImageTagSourceParserInterface;
use Vex6\OpenArticles\Uploader\ArticleImageUploader;

class LogoThumbnailProvider implements ImageProviderInterface
{
    /**
     * @var ImageTagSourceParserInterface
     */
    private $imageTagSourceParser;

    /**
     * @param ImageTagSourceParserInterface $imageTagSourceParser
     */
    public function __construct(
        ImageTagSourceParserInterface $imageTagSourceParser
    ) {
        $this->imageTagSourceParser = $imageTagSourceParser;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($objetId)
    {
        $pathToImage = _PS_MODULE_DIR_ .ArticleImageUploader::IMAGE_PATH. $objetId . '.jpg';

        $imageTag = ImageManager::thumbnail(
            $pathToImage,
            'open_articles_mini_' . $objetId . '.jpg',
            HelperList::LIST_THUMBNAIL_SIZE
        );

        return $this->imageTagSourceParser->parse($imageTag);
    }
}