<?php

namespace Vex6\OpenArticles\CommandHandler;

use Vex6\OpenArticles\Command\BulkEnableArticleCommand;

interface BulkEnableArticleCommandHandlerInterface
{
    /**
     * @param BulkEnableArticleCommand $command
     */
    public function handle(BulkEnableArticleCommand $command);
}