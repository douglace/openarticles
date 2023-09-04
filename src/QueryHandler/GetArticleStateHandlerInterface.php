<?php

namespace Vex6\OpenArticles\QueryHandler;

use Vex6\OpenArticles\Query\GetArticleState;

interface GetArticleStateHandlerInterface
{
    /**
     * @param GetArticleState $query
     *
     * @return bool
     */
    public function handle(GetArticleState $query);
}