<?php

namespace ProjectApi\Entity;

use Ramsey\Uuid\UuidInterface;

class Category
{
    public function __construct(
        private UuidInterface $categoryId,
        private string $name,
        private string $description,
    ) {
    }

    public function id(): string
    {
        return $this->categoryId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }
}

