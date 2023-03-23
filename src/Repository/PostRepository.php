<?php

namespace ProjectApi\Repository;

use ProjectApi\Entity\Post;

interface PostRepository
{
    public function store(Post $post): void;

    // /**
    //  * @return Post[]
    //  */
    public function read(mixed $args): Post;
    public function readBySlug(mixed $args): Post;
    /**
     * @return Post[]
     */
    public function listAllPosts(): array;
    public function update(mixed $inputs, mixed $args): void;
    public function delete(mixed $args): void;
}
