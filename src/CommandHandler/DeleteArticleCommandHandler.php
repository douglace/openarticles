<?php

namespace Vex6\OpenArticles\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Vex6\OpenArticles\Command\DeleteArticleCommand;
use Vex6\OpenArticles\Exception\CannotDeleteArticleException;
use Vex6\OpenArticles\Repository\ArticleRepository;

final class DeleteArticleCommandHandler implements DeleteArticleCommandHandlerInterface
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
     * @throws CannotDeleteArticleException
     */
    public function handle(DeleteArticleCommand $command)
    {
        try {
            $entity = $this->repository->findOneBy([
                'id' => $command->getArticleId()->getValue()
            ]);
            if($entity) {
                $this->entityManager->remove($entity);
                $this->entityManager->flush();
            }

            return true;
        } catch(\Exception $e) {
            throw new CannotDeleteArticleException('An error occured durring delete article');
        }
    }
}