<?php 

declare(strict_types=1);

namespace Vex6\OpenArticles\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction; 
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction; 
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\IdentifierColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;


class ArticleDefinitionFactory extends AbstractGridDefinitionFactory 
{
    const GRID_ID = 'open_article';

    /**
     * {@inheritdoc}
     */
    protected function getId()
    {
        return self::GRID_ID;
    }

    /**
     * {@inheritdoc}
     */
    protected function getName()
    {
        return $this->trans('Mes articles', [], 'Modules.Openarticles.Admin');
    }

    /**
     * {@inheritdoc}
     */
    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add((new IdentifierColumn('article_id'))
                ->setName($this->trans('ID', [], 'Admin.Global'))
                ->setOptions([
                    'identifier_field' => 'article_id',
                    'bulk_field' => 'article_id',
                    'with_bulk_field' => true,
                    'clickable' => false,
                ])
            )->add(
                (new DataColumn('title'))
                    ->setName($this->trans('Titre', [], 'Modules.Openarticles.Admin'))
                    ->setOptions([
                        'field' => 'title',
                ])
            )->add(
                (new DataColumn('product'))
                    ->setName($this->trans('Produit', [], 'Modules.Openarticles.Admin'))
                    ->setOptions([
                        'field' => 'product',
                ])
            )->add( 
                (new ActionColumn('actions')) 
                ->setName($this->trans('Actions', [], 'Admin.Global')) 
                ->setOptions([ 
                    'actions' => (new RowActionCollection()) 
                    ->add( 
                        (new LinkRowAction('edit')) 
                        ->setName($this->trans('Edit', [], 'Admin.Actions')) 
                        ->setIcon('edit') 
                        ->setOptions([ 
                            'route' => 'oit_article_edit', 
                            'route_param_name' => 'articleId', 
                            'route_param_field' => 'article_id', 
                            'clickable_row' => true, 
                        ]) 
                    ) 
                    ->add( 
                        (new SubmitRowAction('delete')) 
                        ->setName($this->trans('Delete', [], 'Admin.Actions')) 
                        ->setIcon('delete') 
                        ->setOptions([ 
                            'route' => 'oit_article_delete', 
                            'route_param_name' => 'articleId', 
                            'route_param_field' => 'article_id',
                            'confirm_message' => $this->trans( 
                                'Delete selected item?', 
                                [], 
                                'Admin.Notifications.Warning' 
                            ), 
                        ]) 
                    ) 
                ]) 
            ) 
        ;
    }
}