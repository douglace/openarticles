<?php

namespace Vex6\OpenArticles\CommandHandler;

use Vex6\OpenArticles\Command\BulkDisableArticleCommand;

interface BulkDisableArticleCommandHandlerInterface
{
    /**
     * @param BulkDisableArticleCommand $command
     */
    public function handle(BulkDisableArticleCommand $command);
}