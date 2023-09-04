<?php

namespace Vex6\OpenArticles\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Vex6\OpenArticles\Command\DeleteArticleCommand;
use Vex6\OpenArticles\Command\UpdateArticlePositionCommand;
use Vex6\OpenArticles\Exception\CannotDeleteArticleException;
use Vex6\OpenArticles\Exception\CannotUpdateArticleException;
use Vex6\OpenArticles\Exception\CannotUpdateArticlePositionException;
use Vex6\OpenArticles\Repository\ArticleRepository;

final class UpdateArticlePositionCommandHandler implements UpdateArticlePositionCommandHandlerInterface
{
    /**
     * @var ArticleRepository
     */
    public $repository;

    /**
     * @var EntityManagerInterface
     */
    public $entityManager;

    /**
     * @param ArticleRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ArticleRepository $repository,
        EntityManagerInterface $entityManager
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws CannotUpdateArticlePositionException
     */
    public function handle(UpdateArticlePositionCommand $command)
    {
        try {
            $this->repository->updatePositions($command->getData());
        } catch(\Exception $e) {
            throw new CannotUpdateArticlePositionException('An error occured durring update article position');
        }
    }
}