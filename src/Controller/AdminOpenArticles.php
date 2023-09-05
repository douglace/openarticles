<?php

namespace Vex6\OpenArticles\Controller;

use PrestaShopBundle\Component\CsvResponse;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Service\Grid\ResponseBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Vex6\OpenArticles\Command\BulkDeleteArticleCommand;
use Vex6\OpenArticles\Command\BulkDisableArticleCommand;
use Vex6\OpenArticles\Command\BulkEnableArticleCommand;
use Vex6\OpenArticles\Command\DeleteArticleCommand;
use Vex6\OpenArticles\Command\ToggleArticleCommand;
use Vex6\OpenArticles\Command\UpdateArticlePositionCommand;
use Vex6\OpenArticles\Exception\CannotDeleteImageArticleException;
use Vex6\OpenArticles\Exception\CannotToggleArticleException;
use Vex6\OpenArticles\Exception\CannotUpdateArticlePositionException;
use Vex6\OpenArticles\Exception\InvalidArticleIdException;
use Vex6\OpenArticles\Exception\InvalidBulkArticleIdException;
use Vex6\OpenArticles\Grid\Definition\Factory\ArticleDefinitionFactory;
use Vex6\OpenArticles\Grid\Filters\ArticleFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Vex6\OpenArticles\Query\GetArticleState;
use Vex6\OpenArticles\Uploader\ArticleImageUploader;


class AdminOpenArticles extends FrameworkBundleAdminController
{
    public function indexAction(Request $request, ArticleFilters $filters)
    {
        $gridFactory = $this->get('openarticles.grid.grid_factory');
        $grid = $gridFactory->getGrid($filters);

        $configFormDataHandler = $this->get('openarticles.form.configuration_data_handler');
        $configForm = $configFormDataHandler->getForm();
        $configForm->handleRequest($request);
        //dump($request->get(''));die;

        if ($configForm->isSubmitted() && $configForm->isValid()) {
            /** You can return array of errors in form handler and they can be displayed to user with flashErrors */
            $errors = $configFormDataHandler->save($configForm->getData());

            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

                return $this->redirectToRoute('oit_article_index');
            }

            $this->flashErrors($errors);
        }

        return $this->render('@Modules/openarticles/views/templates/admin/article.html.twig', [
            'enableSidebar' => true,
            'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
            'layoutTitle' => $this->trans('Liste des articles', 'Modules.Openarticles.Admin'),
            'articleGrid' => $this->presentGrid($grid),
            'configForm' => $configForm->createView(),
        ]);
    }

    /**
     * Provides filters functionality.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function searchAction(Request $request): RedirectResponse
    {
        /** @var ResponseBuilder $responseBuilder */
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('openarticles.grid.definition.factory'),
            $request,
            ArticleDefinitionFactory::GRID_ID,
            'oit_article_index'
        );
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
     * Changes multiple article statuses to enabled.
     *
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function bulkStatusEnableAction(Request $request): RedirectResponse
    {
        $articlesToEnable = $request->request->get('open_article_article_id');

        try {
            $articlesToEnable = array_map(function ($item) { return (int) $item; }, $articlesToEnable);

            $this->getCommandBus()->handle(
                new BulkEnableArticleCommand($articlesToEnable)
            );

            $this->addFlash(
                'success',
                $this->trans('The status has been successfully updated.', 'Admin.Notifications.Success')
            );
        } catch (InvalidArticleIdException|InvalidBulkArticleIdException $e) {
            $this->addFlash(
                'error',
                $this->getErrorMessageForException($e, $this->getErrorMessages())
            );
        }

        return $this->redirectToRoute('oit_article_index');

    }

    /**
     * Changes multiple articles statuses to disable.
     *
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function bulkStatusDisableAction(Request $request): RedirectResponse
    {
        $articlesToDisable = $request->request->get('open_article_article_id');

        try {
            $articlesToDisable = array_map(function ($item) { return (int) $item; }, $articlesToDisable);

            $this->getCommandBus()->handle(
                new BulkDisableArticleCommand($articlesToDisable)
            );

            $this->addFlash(
                'success',
                $this->trans('The status has been successfully updated.', 'Admin.Notifications.Success')
            );
        }  catch (InvalidArticleIdException|InvalidBulkArticleIdException $e) {
            $this->addFlash(
                'error',
                $this->getErrorMessageForException($e, $this->getErrorMessages())
            );
        }

        return $this->redirectToRoute('oit_article_index');
    }

    /**
     * @AdminSecurity("is_granted('update', request.get('_legacy_controller'))", message="Access denied.")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return RedirectResponse
     */
    public function updatePositionsAction(Request $request)
    {
        $positionsData = [
            'positions' => $request->request->get('positions', null),
        ];


        try {
            $this->getCommandBus()->handle(
                new UpdateArticlePositionCommand($positionsData)
            );

            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));
        } catch (CannotUpdateArticlePositionException $e) {
            $this->addFlash(
                'error',
                $this->getErrorMessageForException($e, $this->getErrorMessages())
            );
        }

        return $this->redirectToRoute('oit_article_index');
    }



    /**
     * Toggle category status.
     *
     * @AdminSecurity(
     *     "is_granted(['update'], request.get('_legacy_controller'))",
     *     message="You do not have permission to update this."
     * )
     *
     * @param int $articleId
     *
     * @return JsonResponse
     */
    public function toggleAction(int $articleId): JsonResponse
    {

        try {
            $isEnabled = $this->getQueryBus()->handle(new GetArticleState((int) $articleId));

            $this->getCommandBus()->handle(
                new ToggleArticleCommand((int) $articleId, !$isEnabled)
            );

            $response = [
                'status' => true,
                'message' => $this->trans('The status has been successfully updated.', 'Admin.Notifications.Success'),
            ];
        } catch (CannotToggleArticleException|InvalidArticleIdException $e) {
            $response = [
                'status' => false,
                'message' => $this->getErrorMessageForException($e, $this->getErrorMessages()),
            ];
        }

        return $this->json($response);
    }

    public function exportAction(ArticleFilters $filters): CsvResponse
    {
        $filters = new ArticleFilters(['limit' => null] + $filters->all());
        $articleGridFactory = $this->get('openarticles.grid.grid_factory');
        $articleGrid = $articleGridFactory->getGrid($filters);

        $headers = [
            'articleId' => $this->trans('ID', 'Admin.Global'),
            'langId' => $this->trans('Langue', 'Admin.Global'),
            'title' => $this->trans('Titre', 'Admin.Global'),
            'resume' => $this->trans('Résume', 'Admin.Global'),
            'description' => $this->trans('Description', 'Admin.Global'),
            'position' => $this->trans('Position', 'Admin.Global'),
            'active' => $this->trans('Displayed', 'Admin.Global'),
        ];

        $data = [];

        foreach ($articleGrid->getData()->getRecords()->all() as $record) {

            $data[] = [
                'articleId' => $record['article_id'],
                'langId' => $record['lang_id'],
                'title' => $record['title'],
                'resume' => $record['resume'],
                'description' => $record['description'],
                'position' => $record['position'],
                'active' => $record['active'],
            ];
        }

        return (new CsvResponse())
            ->setData($data)
            ->setHeadersData($headers)
            ->setFileName('oit_article_' . date('Y-m-d_His') . '.csv');
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
            CannotToggleArticleException::class => $this->trans('Unable to toggle Article status.', 'Admin.Notifications.Error'),
            CannotUpdateArticlePositionException::class => $this->trans('Unable to update Article position.', 'Admin.Notifications.Error'),
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