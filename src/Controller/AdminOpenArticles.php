<?php

namespace Vex6\OpenArticles\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vex6\OpenArticles\Command\BulkDeleteArticleCommand;
use Vex6\OpenArticles\Command\DeleteArticleCommand;
use Vex6\OpenArticles\Exception\CannotDeleteImageArticleException;
use Vex6\OpenArticles\Exception\InvalidArticleIdException;
use Vex6\OpenArticles\Exception\InvalidBulkArticleIdException;
use Vex6\OpenArticles\Grid\Filters\ArticleFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Vex6\OpenArticles\Uploader\ArticleImageUploader;


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
        $form = $formBuilder->getFormFor($articleId, [
            'articleId' => $articleId
        ]);
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

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws CannotDeleteImageArticleException
     * @throws InvalidBulkArticleIdException
     */
    public function deleteBulkAction(Request $request)
    {
        $articleToDelete = $request->request->get('open_article_article_id');

        try {
            if(!empty($articleToDelete)) {
                $articleToDelete = array_map(function ($item) { return (int) $item; }, $articleToDelete);

                $this->getCommandBus()->handle(
                    new BulkDeleteArticleCommand($articleToDelete)
                );

                foreach($articleToDelete as $id)
                {
                    $this->deleteUploadedImage($id);
                }

                $this->addFlash(
                    'success',
                    $this->trans('The items has been successfully delete.', 'Admin.Notifications.Success')
                );
            }

        } catch (InvalidArticleIdException $exception) {
            $this->addFlash(
                'error',
                $this->getErrorMessageForException($exception, $this->getErrorMessages())
            );
        }

        return $this->redirectToRoute('oit_article_index');

    }

    /**
     * @param $articleId
     * @param Request $request
     * @return RedirectResponse
     * @throws CannotDeleteImageArticleException|InvalidArticleIdException
     */
    public function deleteAction($articleId, Request $request): RedirectResponse
    {
        $res = $this->getCommandBus()->handle(new DeleteArticleCommand((int) $articleId));

        if ($res)
        {
            $this->deleteUploadedImage($articleId);
            $this->addFlash(
                'success',
                $this->trans('Successful deletion.', 'Admin.Notifications.Success')
            );
        } else {
            $this->addFlash(
                'error',
                $this->trans('Something wen wrong.', 'Admin.Notifications.Success')
            );
        }

        return $this->redirectToRoute('oit_article_index');
    }


    /**
     * @param int $articleId
     * @return RedirectResponse
     */
    public function deleteImageAction(int $articleId): RedirectResponse
    {
        try {
            $this->deleteUploadedImage($articleId);

            $this->addFlash(
                'success',
                $this->trans('The image was successfully deleted.', 'Admin.Notifications.Success')
            );
        } catch (CannotDeleteImageArticleException $e) {
            $this->addFlash('error', $this->getErrorMessageForException($e, $this->getErrorMessages()));
        }

        return $this->redirectToRoute('oit_article_edit', [
            'articleId' => $articleId
        ]);
    }

    /**
     * Get translated error messages for category exceptions
     *
     * @return array
     */
    private function getErrorMessages(): array
    {
        return [
            CannotDeleteImageArticleException::class => $this->trans('Unable to delete image.', 'Admin.Notifications.Error'),
        ];
    }

    /**
     * @param int $articleId
     *
     * @return void
     * @throws CannotDeleteImageArticleException
     */
    private function deleteUploadedImage(int $articleId): void
    {
        $imgPath = _PS_MODULE_DIR_ .ArticleImageUploader::IMAGE_PATH. $articleId . '.jpg';

        if (!file_exists($imgPath)) {
            return;
        }

        if (unlink($imgPath)) {
            return;
        }

        throw new CannotDeleteImageArticleException(sprintf(
            'Cannot delete image with id "%s"',
            $articleId . '.jpg'
        ));
    }
}