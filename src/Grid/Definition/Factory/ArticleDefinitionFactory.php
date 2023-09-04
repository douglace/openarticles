<?php 

declare(strict_types=1);

namespace Vex6\OpenArticles\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction;
use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\LinkGridAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\SimpleGridAction;
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
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use PrestaShopBundle\Form\Admin\Type\YesAndNoChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\DeleteActionTrait;



class ArticleDefinitionFactory extends AbstractGridDefinitionFactory 
{
    use BulkDeleteActionTrait;
    use DeleteActionTrait;

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
    protected function getGridActions()
    {
        return (new GridActionCollection())
            ->add(
                (new LinkGridAction('export'))
                    ->setName($this->trans('Export', [], 'Admin.Actions'))
                    ->setIcon('cloud_download')
                    ->setOptions([
                        'route' => 'oit_article_export',
                    ])
            )
            ->add((new SimpleGridAction('common_refresh_list'))
                ->setName($this->trans('Refresh list', [], 'Admin.Advparameters.Feature'))
                ->setIcon('refresh')
            )
            ->add((new SimpleGridAction('common_show_query'))
                ->setName($this->trans('Show SQL query', [], 'Admin.Actions'))
                ->setIcon('code')
            )
            ->add((new SimpleGridAction('common_export_sql_manager'))
                ->setName($this->trans('Export to SQL Manager', [], 'Admin.Actions'))
                ->setIcon('storage')
            )
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFilters()
    {
        return (new FilterCollection())
            ->add(
                (new Filter('article_id', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('ID', [], 'Admin.Global'),
                        ],
                    ])
                    ->setAssociatedColumn('article_id')
            )->add(
                (new Filter('title', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Titre', [], 'Admin.Global'),
                        ],
                    ])
                    ->setAssociatedColumn('title')
            )->add(
                (new Filter('product', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Produit', [], 'Admin.Global'),
                        ],
                    ])
                    ->setAssociatedColumn('product')
            )->add(
                (new Filter('position', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Position', [], 'Admin.Global'),
                        ],
                    ])
                    ->setAssociatedColumn('position')
            )->add(
                (new Filter('active', YesAndNoChoiceType::class))
                    ->setAssociatedColumn('active')
            )->add(
                (new Filter('actions', SearchAndResetType::class))
                    ->setTypeOptions([
                        'reset_route' => 'admin_common_reset_search_by_filter_id',
                        'reset_route_params' => [
                            'filterId' => self::GRID_ID,
                        ],
                        'redirect_route' => 'oit_article_search',
                    ])
                    ->setAssociatedColumn('actions')
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