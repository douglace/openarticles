<?php

namespace Vex6\OpenArticles\Form\Provider;

use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class ArticleFormDataProvider implements FormDataProviderInterface
{
    /**
     * @param int $id
     * @return array
     */
    public function getData($id): array
    {
        return [

        ];
    }

    public function getDefaultData(): array
    {
        return [
          'active' => true
        ];
    }
}