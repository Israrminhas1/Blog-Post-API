<?php

namespace ProjectApi\Repository;

use ProjectApi\Entity\Post;

interface PostsCategoriesRepository
{
    public function store(Post $post): void;
}

