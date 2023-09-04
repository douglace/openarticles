<?php

namespace Vex6\OpenArticles\Command;

use Vex6\OpenArticles\Command\ArticleIdentifierCommandInterface;
use Vex6\OpenArticles\Exception\CannotToggleArticleException;
use Vex6\OpenArticles\Exception\InvalidArticleIdException;
use Vex6\OpenArticles\ValueObject\ArticleId;

final class ToggleArticleCommand implements ArticleIdentifierCommandInterface
{
    use ArticleIdentifierCommandTrait;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @param ArticleId|int $articleId
     * @throws InvalidArticleIdException
     * @throws CannotToggleArticleException
     */
    public function __construct($articleId, $active)
    {
        if(is_int($articleId)) {
            $articleId = new ArticleId($articleId);
        } elseif (!($articleId instanceof ArticleId)) {
            throw new CannotToggleArticleException("The article Id is invalid");
        }
        $this->articleId = $articleId;
        $this->active = $active;
    }

    /**
     * Get the value of active
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set the value of active
     */
    public function setActive($active): self
    {
        $this->active = $active;

        return $this;
    }
}