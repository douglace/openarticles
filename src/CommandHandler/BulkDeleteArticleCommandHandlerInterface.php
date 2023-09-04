<?php

namespace Vex6\OpenArticles\CommandHandler;

use Vex6\OpenArticles\Command\BulkDeleteArticleCommand;

interface BulkDeleteArticleCommandHandlerInterface
{
    /**
     * @param BulkDeleteArticleCommand $command
     */
    public function handle(BulkDeleteArticleCommand $command);
}