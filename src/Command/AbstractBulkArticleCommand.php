<?php

namespace Vex6\OpenArticles\Command;

use Vex6\OpenArticles\Exception\InvalidArticleIdException;
use Vex6\OpenArticles\Exception\InvalidBulkArticleIdException;
use Vex6\OpenArticles\ValueObject\ArticleId;

abstract class AbstractBulkArticleCommand
{
    /**
     * @var ArticleId[]
     */
    private $articleIds;

    /**
     * @param int[] $articleIds
     *
     * @throws InvalidBulkArticleIdException
     * @throws InvalidArticleIdException
     */
    public function __construct(array $articleIds)
    {
        if ($this->assertIsEmptyOrContainsNonIntegerValues($articleIds)) {
            throw new InvalidBulkArticleIdException('Missing article data or array contains non integer values for bulk enabling');
        }

        $this->setArticleIds($articleIds);
    }

    /**
     * @return ArticleId[]
     */
    public function getArticleIds(): array
    {
        return $this->articleIds;
    }

    /**
     * @param int[] $articleIds
     *
     * @throws InvalidArticleIdException
     */
    private function setArticleIds(array $articleIds): void
    {
        foreach ($articleIds as $id) {
            $this->articleIds[] = new ArticleId($id);
        }
    }

    /**
     * @param array $ids
     *
     * @return bool
     */
    protected function assertIsEmptyOrContainsNonIntegerValues(array $ids): bool
    {
        return empty($ids) || $ids !== array_filter($ids, 'is_int');
    }
}