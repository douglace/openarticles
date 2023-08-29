<?php

namespace Vex6\OpenArticles\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vex6\OpenArticles\Grid\Filters\ArticleFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;


class AdminOpenArticles extends FrameworkBundleAdminController
{
    public function indexAction(Request $request, ArticleFilters $filters)
    {
        $gridFactory = $this->get('openarticles.grid.grid_factory');
        $grid = $gridFactory->getGrid($filters);

        return $this->render('@Modules/openarticles/views/templates/admin/article.html.twig', [
            'enableSidebar' => true,
            'layoutTitle' => $this->trans('Liste des articles', 'Modules.Openarticles.Admin'),
            'articleGrid' => $this->presentGrid($grid),
        ]);
    }
}