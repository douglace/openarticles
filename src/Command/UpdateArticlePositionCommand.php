<?php

namespace Vex6\OpenArticles\Command;

use Vex6\OpenArticles\Command\ArticleIdentifierCommandInterface;
use Vex6\OpenArticles\Exception\InvalidArticleIdException;
use Vex6\OpenArticles\ValueObject\ArticleId;

final class UpdateArticlePositionCommand
{

    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): UpdateArticlePositionCommand
    {
        $this->data = $data;
        return $this;
    }
}