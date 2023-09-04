<?php 

declare(strict_types=1);

namespace Vex6\OpenArticles\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollectionInterface;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ImageColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\PositionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ToggleColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction; 
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction; 
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\IdentifierColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\BulkDeleteActionTrait;


class ArticleDefinitionFactory extends AbstractGridDefinitionFactory 
{
    use BulkDeleteActionTrait;
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
            ) ->add((new ImageColumn('logo'))
                ->setName($this->trans('Image', [], 'Admin.Global'))
                ->setOptions([
                    'src_field' => 'logo',
                ])
            )
            ->add(
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
                (new ToggleColumn('active'))
                    ->setName($this->trans('Displayed', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'active',
                        'primary_field' => 'article_id',
                        'route' => 'oit_toggle_status',
                        'route_param_name' => 'articleId',
                    ])
            )->add(
                (new PositionColumn('position'))
                    ->setName($this->trans('Position', [], 'Admin.Global'))
                    ->setOptions([
                        'id_field' => 'article_id',
                        'position_field' => 'position',
                        'update_route' => 'oit_update_positions',
                        'update_method' => 'POST',
                        'record_route_params' => [
                            'article_id' => 'articleId',
                        ],
                    ])
            ) ->add(
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


    /**
     * {@inheritdoc}
     */
    protected function getBulkActions()
    {
        return (new BulkActionCollection())
            ->add((new SubmitBulkAction('enable_selection'))
                ->setName($this->trans('Enable selection', [], 'Admin.Actions'))
                ->setOptions([
                    'submit_route' => 'oit_bulk_status_enable',
                ])
            )
            ->add((new SubmitBulkAction('disable_selection'))
                ->setName($this->trans('Disable selection', [], 'Admin.Actions'))
                ->setOptions([
                    'submit_route' => 'oit_bulk_status_disable',
                ])
            )
            ->add(
                $this->buildBulkDeleteAction('oit_delete_bulk')
            )
            ;
    }
}