<?php

namespace ProjectApi\Repository;

use ProjectApi\Controller\FileController;
use ProjectApi\Entity\Post;
use PDO;

class PostRepositoryFromPdo implements PostRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    private function storeQuery(): string
    {
        return <<<SQL
            INSERT INTO posts (id, title, slug, content, thumbnail, author, posted_at)
            VALUES (:id, :title, :slug, :content, :thumbnail, :author, :posted_at)
        SQL;
    }

    private function updateQuery(): string
    {
        return <<<SQL
            UPDATE posts
            SET content = :content, thumbnail = :thumbnail
            WHERE id = :id
        SQL;
    }

    public function store(Post $post): void
    {
        $sql = $this->storeQuery();
        $stm = $this->pdo->prepare($sql);

        $params = [
            ':id' => $post->id(),
            ':title' => $post->title(),
            ':slug' => $post->slug(),
            ':content' => $post->content(),
            ':thumbnail' => $post->thumbnail(),
            ':author' => $post->author(),
            ':posted_at' => $post->postedAt(),
        ];

        $stm->execute($params);
    }

    /**
     * @return Post[]
     */
    public function read(mixed $args): array
    {
        $stm = $this->pdo->prepare(<<<SQL
            SELECT posts.*, categories.id as category_id ,categories.name,categories.description
            FROM posts 
            JOIN posts_categories ON posts.id = posts_categories.post_id 
            JOIN categories ON posts_categories.category_id = categories.id 
            WHERE posts.id = :id
        SQL);
        $stm->bindParam(':id', $args['id']);
        $stm->execute();
        $data = $stm->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($data)) {
            return [];
        }

        $post = [];
        $categories = [];

        foreach ($data as $row) {
            if (!isset($post['id'])) {
                $post = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'slug' => $row['slug'],
                    'content' => $row['content'],
                    'thumbnail' => $row['thumbnail'],
                    'author' => $row['author'],
                    'posted_at' => $row['posted_at']
                ];
            }

            $categories[] = [
                'id' => $row['category_id'],
                'name' => $row['name'],
                'description' => $row['description']
            ];
        }

        $post['categories'] = $categories;

        return $post;
    }
    /**
     * @return Post[]
     */
    public function readBySlug(mixed $args): array
    {
        $stm = $this->pdo->prepare(<<<SQL
            SELECT posts.*, categories.id as category_id ,categories.name,categories.description
            FROM posts 
            JOIN posts_categories ON posts.id = posts_categories.post_id 
            JOIN categories ON posts_categories.category_id = categories.id 
            WHERE posts.slug = :slug
        SQL);
        $stm->bindParam(':slug', $args['slug']);
        $stm->execute();
        $data = $stm->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($data)) {
            return [];
        }

        $post = [];
        $categories = [];

        foreach ($data as $row) {
            if (!isset($post['id'])) {
                $post = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'slug' => $row['slug'],
                    'content' => $row['content'],
                    'thumbnail' => $row['thumbnail'],
                    'author' => $row['author'],
                    'posted_at' => $row['posted_at']
                ];
            }

            $categories[] = [
                'id' => $row['category_id'],
                'name' => $row['name'],
                'description' => $row['description']
            ];
        }

        $post['categories'] = $categories;

        return $post;
    }

    /**
     * @return Post[]
     */
    public function listAllPosts(): array
    {
        $stm = $this->pdo->prepare('SELECT * FROM posts');
        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function update(mixed $inputs, mixed $args): void
    {
        $sql = $this->updateQuery();
        $stm = $this->pdo->prepare($sql);

        $thumbnail = new FileController($inputs['thumbnail']);
        $filePath = 'http://localhost:8889/images/' . $thumbnail->handle();

        $params = [
            ':id' => $args['id'],
            ':content' => $inputs['content'],
            ':thumbnail' => $filePath
        ];

        $stm->execute($params);
    }

    public function delete(mixed $args): void
    {
        $stm = $this->pdo->prepare('DELETE FROM posts WHERE id = :id');
        $stm->bindParam(':id', $args['id']);
        $stm->execute();
    }
}
