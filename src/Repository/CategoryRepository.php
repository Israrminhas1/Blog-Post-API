<?php

namespace ProjectApi\Repository;

use ProjectApi\Entity\Category;

interface CategoryRepository
{
    public function store(Category $category): void;
    public function read(mixed $args): Category;
    public function listAllCategories(): array;
    public function update(mixed $inputs, mixed $args): void;
    public function delete(mixed $args): void;
    public function getByIdString($id): Category;
}
