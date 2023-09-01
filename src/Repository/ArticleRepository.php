<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\Repository;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{

    public function getProducts(int $lang_id = 0)
    {
        $lang_id = $lang_id ? $lang_id : \Context::getContext()->language->id;
        $query = new \DbQuery();
        $query->from('product_lang')
            ->select('name, id_product')
            ->where('id_lang='.$lang_id)
        ;

        $products = \Db::getInstance()->executeS($query);

        $data = [];
        foreach ($products as $p) {
            $data[$p['name']] = $p['id_product'];
        }

        return $data;
    }

}