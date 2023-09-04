<?php

namespace Vex6\OpenArticles\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Vex6\OpenArticles\Command\BulkDisableArticleCommand;
use Vex6\OpenArticles\Exception\InvalidArticleIdException;
use Vex6\OpenArticles\Repository\ArticleRepository;

final class BulkDisableArticleCommandHandler implements BulkDisableArticleCommandHandlerInterface
{

    /**
     * @param ArticleRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        ArticleRepository $repository,
        EntityManagerInterface $entityManager
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }


    /**
     * @throws InvalidArticleIdException
     */
    public function handle(BulkDisableArticleCommand $command)
    {
        try {
            foreach ($command->getArticleIds() as $articleId) {

                $entity = $this->repository->findOneBy([
                    'id' => $articleId->getValue()
                ]);

                if(!$entity) {
                    throw new InvalidArticleIdException(sprintf('Article object with id "%s" has not been found for Disabling status.', $articleId->getValue()));
                }

                $entity->setActive(false);
                $this->entityManager->merge($entity);
                $this->entityManager->flush();

            }
        } catch (Exception $e) {
            throw new InvalidArticleIdException('Unexpected error occurred when handling bulk Disable article', 0, $e);
        }
    }
}