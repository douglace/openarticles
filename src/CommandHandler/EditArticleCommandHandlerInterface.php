<?php

namespace Vex6\OpenArticles\CommandHandler;

use Vex6\OpenArticles\Command\EditArticleCommand;
use Vex6\OpenArticles\ValueObject\ArticleId;

interface EditArticleCommandHandlerInterface
{
    public function handle(EditArticleCommand $command): ArticleId;
}