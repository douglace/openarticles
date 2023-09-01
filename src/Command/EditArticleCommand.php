<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\Command;

use Vex6\OpenArticles\ValueObject\ArticleId;

final class EditArticleCommand extends AbstractArticleCommand implements ArticleIdentifierCommandInterface
{
    /**
     * @var ArticleId
     */
    private $articleId;

    /**
     * @param ArticleId $articleId
     */
    public function __construct(ArticleId $articleId)
    {
        $this->articleId = $articleId;
    }

    public function getArticleId(): ArticleId
    {
        return $this->articleId;
    }
}