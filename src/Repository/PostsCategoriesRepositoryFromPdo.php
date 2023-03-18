<?php

namespace ProjectApi\Repository;

use ProjectApi\Entity\Post;
use PDO;

class PostsCategoriesRepositoryFromPdo implements PostsCategoriesRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    private function storeQuery(): string
    {
        return <<<SQL
            INSERT INTO posts_categories (post_id, category_id)
            VALUES (:post_id, :category_id)
        SQL;
    }

    public function store(Post $post): void
    {
        $sql = $this->storeQuery();
        $stm = $this->pdo->prepare($sql);

        foreach ($post->categories() as $category) {
            $params = [
                ':post_id' => $post->id(),
                ':category_id' => $category['id'],
            ];

            $stm->execute($params);
        }
    }
}
