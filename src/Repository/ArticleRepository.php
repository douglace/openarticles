<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\Repository;

use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Driver\Statement;
use Vex6\OpenArticles\Uploader\ArticleImageUploader;

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

    /**
     * @param array $positionsData
     * @return void
     * @throws ConnectionException
     */
    public function updatePositions(array $positionsData = []): void
    {
        try {
            $this->_em->getConnection()->beginTransaction();

            $i = 0;
            foreach ($positionsData['positions'] as $position) {
                $qb = $this->_em->getConnection()->createQueryBuilder();
                $qb
                    ->update(_DB_PREFIX_ . 'open_articles')
                    ->set('position', ':position')
                    ->andWhere('id = :articleId')
                    ->setParameter('articleId', $position['rowId'])
                    ->setParameter('position', $i);

                ++$i;

                $qb->execute();
            }
            $this->_em->getConnection()->commit();
        } catch (Exception $e) {
            $this->_em->getConnection()->rollBack();
        }
    }

    public function getFrontData(int $id_lang, int $limit = 6)
    {
        $qb = $this->_em->getConnection()->createQueryBuilder()
            ->from(_DB_PREFIX_ . 'open_articles', 'oa')
            ->leftJoin(
                'oa',
                _DB_PREFIX_ . 'open_articles_lang',
                'oal',
                'oal.`open_article_id` = oa.`id` AND oal.`lang_id` = :lang'
            )->leftJoin(
                'oa',
                _DB_PREFIX_ . 'product_lang',
                'pl',
                'pl.`id_product` = oa.`product_id` AND pl.`id_lang` = :lang'
            )
            ->select('oa.id, oa.active, oa.position, oal.title, oal.resume, oal.description')
            ->addSelect('pl.`name` product_name')
            ->andWhere('oal.lang_id = :lang')
            ->setParameter('lang', $id_lang)
            ->orderBy('oa.position', 'ASC')
        ;


        $image_dir = _PS_MODULE_DIR_.ArticleImageUploader::IMAGE_PATH;
        $image_path = _MODULE_DIR_.ArticleImageUploader::IMAGE_PATH;

        $items = array_map(function($a)use($image_path,$image_dir){
            $a['image'] = file_exists($image_dir.$a['id'].'.jpg') ?
                $image_path.$a['id'].'.jpg' :
                null
            ;
            return $a;
        }, $qb->execute()->fetchAllAssociative());

        return $items;
    }

}