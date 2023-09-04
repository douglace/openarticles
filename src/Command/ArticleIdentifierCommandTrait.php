<?php

namespace Vex6\OpenArticles\Command;

use Vex6\OpenArticles\Exception\InvalidArticleIdException;
use Vex6\OpenArticles\ValueObject\ArticleId;

trait ArticleIdentifierCommandTrait
{
    /**
     * @var ArticleId
     */
    private $articleId;

    /**
     * @param ArticleId $articleId
     * @throws InvalidArticleIdException
     */
    public function __construct($articleId)
    {
        if(is_int($articleId)) {
            $articleId = new ArticleId($articleId);
        } elseif (!($articleId instanceof ArticleId)) {
            throw new InvalidArticleIdException("The article Id is invalid");
        }
        $this->articleId = $articleId;
    }

    public function getArticleId(): ArticleId
    {
        return $this->articleId;
    }
}