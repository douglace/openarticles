<?php

namespace Vex6\OpenArticles\CommandHandler;

use Vex6\OpenArticles\Command\UpdateArticlePositionCommand;

interface UpdateArticlePositionCommandHandlerInterface
{
    public function handle(UpdateArticlePositionCommand $command);
}