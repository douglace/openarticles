<?php

namespace Vex6\OpenArticles\CommandBuilder;

use Vex6\OpenArticles\Command\AddArticleCommand;
use Vex6\OpenArticles\Command\ArticleCommandInterface;
use Vex6\OpenArticles\Command\EditArticleCommand;
use Vex6\OpenArticles\ValueObject\ArticleId;

class ArticleCommandBuilder implements ArticleCommandBuilderInterface
{
    public function buildAddCommand(array $data): AddArticleCommand
    {
        $command = new AddArticleCommand();
        $this->build($command, $data);
        return $command;
    }

    public function buildEditCommand(ArticleId $articleId, array $data): EditArticleCommand
    {
        $command = new EditArticleCommand($articleId);
        $this->build($command, $data);
        return $command;
    }

    /**
     * @param ArticleCommandInterface $command
     * @param array $data
     * @return void
     */
    private function build(ArticleCommandInterface $command, array $data)
    {
        if (isset($data['active'])) {
            $command->setActive((bool) $data['active']);
        }

        if (isset($data['position'])) {
            $command->setPosition((int) $data['position']);
        }

        if (isset($data['product_id'])) {
            $command->setProductId((int) $data['product_id']);
        }

        if (isset($data['title'])) {
            $command->setTitle((array) $data['title']);
        }

        if (isset($data['resume'])) {
            $command->setResume((array) $data['resume']);
        }

        if (isset($data['description'])) {
            $command->setDescription((array) $data['description']);
        }
    }
}