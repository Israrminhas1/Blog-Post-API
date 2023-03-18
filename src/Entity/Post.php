<?php

namespace ProjectApi\Entity;

use Ramsey\Uuid\UuidInterface;

class Post
{
    /**
     * @param Category[] $categories
     */
    public function __construct(
        private UuidInterface $postId,
        private string $title,
        private string $slug,
        private string $content,
        private string $thumbnail,
        private string $author,
        private string $posted_at,
        private array $categories,
    ) {
    }

    public function id(): string
    {
        return $this->postId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function thumbnail(): string
    {
        return $this->thumbnail;
    }

    public function author(): string
    {
        return $this->author;
    }

    public function postedAt(): string
    {
        return $this->posted_at;
    }
    public function categories(): array
    {
        return $this->categories;
    }


}
