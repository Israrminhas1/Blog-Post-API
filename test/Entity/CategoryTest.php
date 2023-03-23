<?php

namespace ApiTest\Entity;

use ProjectApi\Entity\Category;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testCategories(): void
    {
        $id = Uuid::uuid4();
        $name = 'Category name';
        $description = 'Category description';

        $categories = new Category(
            $id,
            $name,
            $description,
        );

        self::assertEquals($id, $categories->id());
        self::assertEquals($name, $categories->name());
        self::assertEquals($description, $categories->description());
    }
}