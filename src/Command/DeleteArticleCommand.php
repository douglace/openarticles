<?php

namespace Vex6\OpenArticles\Command;

use Vex6\OpenArticles\Command\ArticleIdentifierCommandInterface;
use Vex6\OpenArticles\ValueObject\ArticleId;

final class DeleteArticleCommand implements ArticleIdentifierCommandInterface
{
    use ArticleIdentifierCommandTrait;
}