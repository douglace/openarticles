<?php

namespace Vex6\OpenArticles\Command;

interface ArticleCommandInterface
{
    public function getProductId(): int;

    public function getPosition(): int;

    public function getActive(): bool;

    public function getTitle(): array;

    public function getResume(): array;

    public function getDescription(): array;
}