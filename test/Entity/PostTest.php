<?php

namespace ApiTest\Entity;

use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use ProjectApi\Entity\Post;
use DateTimeImmutable;

class PostTest extends TestCase
{
    public function testPosts(): void
    {
        $id = Uuid::uuid4();
        $title = 'Post title';
        $slug = 'Post slug';
        $content = 'Post content';
        $thumbnail = 'image.jpeg';
        $author = 'Krivan Raul';
        $posted_at = new DateTimeImmutable();

        $posts = new Post(
            $id,
            $title,
            $slug,
            $content,
            $thumbnail,
            $author,
            $posted_at
        );

        self::assertEquals($id, $posts->id());
        self::assertEquals($title, $posts->title());
        self::assertEquals($slug, $posts->slug());
        self::assertEquals($content, $posts->content());
        self::assertEquals($thumbnail, $posts->thumbnail());
        self::assertEquals($author, $posts->author());
        self::assertEquals($posted_at, $posts->postedAt());
    }
}