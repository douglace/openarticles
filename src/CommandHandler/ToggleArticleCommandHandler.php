<?php

namespace Vex6\OpenArticles\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Vex6\OpenArticles\Command\ToggleArticleCommand;
use Vex6\OpenArticles\Exception\CannotDeleteArticleException;
use Vex6\OpenArticles\Exception\CannotToggleArticleException;
use Vex6\OpenArticles\Repository\ArticleRepository;

final class ToggleArticleCommandHandler implements ToggleArticleCommandHandlerInterface
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
     * @throws CannotToggleArticleException
     */
    public function handle(ToggleArticleCommand $command)
    {
        try {
            $entity = $this->repository->findOneBy([
                'id' => $command->getArticleId()->getValue()
            ]);

            if($entity) {
                $entity->setActive($command->getActive());
                $this->entityManager->merge($entity);
                $this->entityManager->flush();
                return $command->getArticleId();
            } else {
                throw new CannotToggleArticleException("Unable to find article");
            }


        } catch(\Exception $e) {
            throw new CannotToggleArticleException('An error occured durring toggle article state');
        }
    }
}