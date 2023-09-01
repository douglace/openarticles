<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\Form\Provider;

use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;
use Vex6\OpenArticles\Repository\ArticleRepository;

class ArticleFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @param ArticleRepository $repository
     */
    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $articleId
     * @return array
     */
    public function getData($articleId): array
    {
        $article = $this->repository->find($articleId);

        $data = [
            'active' => $article->getActive(),
            'product_id' => $article->getProductId(),
        ];

        foreach ($article->getArticleLangs() as $articleLang) {
            $data['title'][$articleLang->getLang()->getId()] = $articleLang->getTitle();
            $data['resume'][$articleLang->getLang()->getId()] = $articleLang->getResume();
            $data['description'][$articleLang->getLang()->getId()] = $articleLang->getDescription();
        }

        return $data;
    }

    public function getDefaultData(): array
    {
        return [
          'active' => true
        ];
    }
}