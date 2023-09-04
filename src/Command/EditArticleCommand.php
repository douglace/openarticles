<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\Command;

use Vex6\OpenArticles\ValueObject\ArticleId;

final class EditArticleCommand extends AbstractArticleCommand implements ArticleIdentifierCommandInterface
{
    use ArticleIdentifierCommandTrait;
}