<?php

namespace Vex6\OpenArticles\CommandHandler;

use Vex6\OpenArticles\Command\ToggleArticleCommand;

interface ToggleArticleCommandHandlerInterface
{
    public function handle(ToggleArticleCommand $command);
}