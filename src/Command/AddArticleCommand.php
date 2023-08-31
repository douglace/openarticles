<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\Command;

final class AddArticleCommand implements ArticleCommandInterface
{
    /**
     * @var int
     */
    private $productId;

    /**
     * @var int
     */
    private $position;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var string[]
     */
    private $title;

    /**
     * @var string[]
     */
    private $resume;

    /**
     * @var string[]
     */
    private $description;

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): AddArticleCommand
    {
        $this->productId = $productId;
        return $this;
    }

    public function getPosition(): int
    {
        return $this->position ?? 0;
    }

    public function setPosition(int $position): AddArticleCommand
    {
        $this->position = $position;
        return $this;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): AddArticleCommand
    {
        $this->active = $active;
        return $this;
    }

    public function getTitle(): array
    {
        return $this->title;
    }

    public function setTitle(array $title): AddArticleCommand
    {
        $this->title = $title;
        return $this;
    }

    public function getResume(): array
    {
        return $this->resume;
    }

    public function setResume(array $resume): AddArticleCommand
    {
        $this->resume = $resume;
        return $this;
    }

    public function getDescription(): array
    {
        return $this->description;
    }

    public function setDescription(array $description): AddArticleCommand
    {
        $this->description = $description;
        return $this;
    }

}