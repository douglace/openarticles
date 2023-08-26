<?php

namespace Vex6\OpenArticles\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Response;

class AdminOpenArticles extends FrameworkBundleAdminController
{
    public function indexAction()
    {
        return $this->render('@Modules/openarticles/views/templates/admin/article.html.twig', [
            'enableSidebar' => true,
            'layoutTitle' => $this->trans('Liste des articles', 'Modules.Openarticles.Admin'),
        ]);
    }
}