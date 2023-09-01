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
            'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
            'layoutTitle' => $this->trans('Liste des articles', 'Modules.Openarticles.Admin'),
            'articleGrid' => $this->presentGrid($grid),
        ]);
    }

    public function getToolbarButtons(): array
    {
        return [
            'add' => [
                'desc' => $this->trans('Add new article', 'Modules.Openarticles.Admin'),
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('oit_article_create'),
            ],
        ];
    }

    public function createAction(Request $request) {
        $formBuilder = $this->get('openarticles.form.identifiable.object.builder');
        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        $formHandler = $this->get('openarticles.form.identifiable.object.handler');
        $result = $formHandler->handle($form);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('oit_article_index');
        }


        return $this->render('@Modules/openarticles/views/templates/admin/create.html.twig', [
            'articleForm' => $form->createView()
        ]);
    }

    public function editAction($articleId, Request $request)
    {
        $formBuilder = $this->get('openarticles.form.identifiable.object.builder');
        $form = $formBuilder->getFormFor($articleId);
        $form->handleRequest($request);

        $formHandler = $this->get('openarticles.form.identifiable.object.handler');
        $result = $formHandler->handleFor($articleId, $form);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('oit_article_index');
        }

        return $this->render('@Modules/openarticles/views/templates/admin/create.html.twig', [
            'articleForm' => $form->createView()
        ]);
    }
}