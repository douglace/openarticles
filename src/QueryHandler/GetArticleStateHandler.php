<?php

namespace Vex6\OpenArticles\QueryHandler;

use Vex6\OpenArticles\Exception\InvalidArticleIdException;
use Vex6\OpenArticles\Query\GetArticleState;
use Vex6\OpenArticles\Repository\ArticleRepository;

class GetArticleStateHandler implements GetArticleStateHandlerInterface
{
    /**
     * @var ArticleRepository
     */
    private $repository;


    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     * @throws InvalidArticleIdException
     */
    public function handle(GetArticleState $query): bool
    {
        $articleId = $query->getArticleId()->getValue();
        $article = $this->repository->findOneBy([
            'id' => $articleId
        ]);

        if ($article->getId() !== $articleId) {
            throw new InvalidArticleIdException(sprintf('Article with id "%s" was not found.', $articleId));
        }

        return (bool) $article->getActive();
    }
}