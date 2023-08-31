<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\CommandBuilder;

use Vex6\OpenArticles\Command\AddArticleCommand;

interface ArticleCommandBuilderInterface
{

    /**
     * Create new Article command Add
     * @param array $data
     * @return mixed
     */
    public function buildAddCommand(array $data): AddArticleCommand;
}