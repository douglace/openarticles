<?php

namespace Vex6\OpenArticles\Form\DataHandler;

use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use Vex6\OpenArticles\CommandBuilder\ArticleCommandBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vex6\OpenArticles\Uploader\ArticleImageUploader;
use Vex6\OpenArticles\ValueObject\ArticleId;

class ArticleFormDataHandler implements FormDataHandlerInterface
{
    /**
     * @var CommandBusInterface
     */
    private $commandBus;

    /**
     * @var ArticleCommandBuilderInterface
     */
    private $builder;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        CommandBusInterface $commandBus,
        ArticleCommandBuilderInterface $builder
    )
    {
        $this->commandBus = $commandBus;
        $this->builder = $builder;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $command = $this->builder->buildAddCommand($data);
        $articleId = $this->commandBus->handle($command);
        $this->uploadImage($articleId, $data);
        return $articleId;
    }

    /**
     * @param int $articleId
     * @param array $data
     * @return mixed
     * @throws \Vex6\OpenArticles\Exception\InvalidArticleIdException
     */
    public function update($articleId, array $data)
    {
        $command = $this->builder->buildEditCommand(new ArticleId($articleId), $data);
        $articleId = $this->commandBus->handle($command);
        $this->uploadImage($articleId, $data);
        return $articleId;
    }

    private function uploadImage(ArticleId $articleId, array $data)
    {
        $image = isset($data['cover_image']) && $data['cover_image']
            ? $data['cover_image']
            : null
        ;

        if($image == null || !($image instanceof UploadedFile)) {
            return false;
        }

        $uploader = new ArticleImageUploader();
        $uploader->upload($articleId->getValue(), $image);
    }
}