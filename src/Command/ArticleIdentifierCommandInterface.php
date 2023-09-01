<?php

namespace Vex6\OpenArticles\Command;

use Vex6\OpenArticles\ValueObject\ArticleId;

interface ArticleIdentifierCommandInterface
{
    public function getArticleId(): ArticleId;
}