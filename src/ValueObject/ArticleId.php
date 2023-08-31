<?php

namespace Vex6\OpenArticles\ValueObject;

use Vex6\OpenArticles\Exception\InvalidArticleIdException;

final class ArticleId
{
    /**
     * @var int
     */
    private $articleId;

    /**
     * @param int $articleId
     * @throws InvalidArticleIdException
     */
    public function __construct(int $articleId)
    {
        $this->assertIsGreaterThanZero($articleId);
        $this->articleId = $articleId;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->articleId;
    }

    /**
     * @param int $articleId
     * @return void
     * @throws InvalidArticleIdException
     */
    private function assertIsGreaterThanZero(int $articleId): void
    {
        if (0 >= $articleId) {
            throw new InvalidArticleIdException(
                sprintf(
                    'Article id %s is invalid. Article id must be number that is greater than zero.',
                    var_export($articleId, true)
                )
            );
        }
    }
}