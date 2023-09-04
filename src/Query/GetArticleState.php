<?php

namespace Vex6\OpenArticles\Query;

use Vex6\OpenArticles\Exception\InvalidArticleIdException;
use Vex6\OpenArticles\ValueObject\ArticleId;

class GetArticleState
{
    /**
     * @var ArticleId
     */
    private $articleId;

    /**
     * @param int $articleId
     * @throws InvalidArticleIdException
     */
    public function __construct($articleId)
    {
        $this->articleId = new ArticleId($articleId);
    }

    /**
     * @return ArticleId
     */
    public function getArticleId(): ArticleId
    {
        return $this->articleId;
    }
}