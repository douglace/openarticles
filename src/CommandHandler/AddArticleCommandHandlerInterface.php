<?php

namespace Vex6\OpenArticles\CommandHandler;

use Vex6\OpenArticles\Command\AddArticleCommand;
use Vex6\OpenArticles\ValueObject\ArticleId;

interface AddArticleCommandHandlerInterface
{
    public function handle(AddArticleCommand $command): ArticleId;
}