<?php

namespace Vex6\OpenArticles\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Vex6\OpenArticles\Command\BulkDeleteArticleCommand;
use Vex6\OpenArticles\Exception\InvalidArticleIdException;
use Vex6\OpenArticles\Repository\ArticleRepository;

final class BulkDeleteArticleCommandHandler implements BulkDeleteArticleCommandHandlerInterface
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
    public function handle(BulkDeleteArticleCommand $command)
    {
        try {
            foreach ($command->getArticleIds() as $articleId) {

                $entity = $this->repository->findOneBy([
                    'id' => $articleId->getValue()
                ]);

                if(!$entity) {
                    throw new InvalidArticleIdException(sprintf('Article object with id "%s" has not been found for enabling status.', $articleId->getValue()));
                }

                $this->entityManager->remove($entity);
                $this->entityManager->flush();

            }
        } catch (Exception $e) {
            throw new InvalidArticleIdException('Unexpected error occurred when handling bulk Delete push', 0, $e);
        }
    }
}