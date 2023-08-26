<?php

namespace Vex6\OpenArticles\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Response;

class AdminOpenArticles extends FrameworkBundleAdminController
{
    public function indexAction()
    {
        return new Response("Bonjour le monde");
    }
}