<?php

namespace Vex6\OpenArticles\CommandHandler;

use Vex6\OpenArticles\Command\DeleteArticleCommand;

interface DeleteArticleCommandHandlerInterface
{
    public function handle(DeleteArticleCommand $command);
}