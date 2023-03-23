<?php

namespace ProjectApi\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity, ORM\Table(name: 'posts')]

class Post
{
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'posts')]
    private Collection $categories;
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
        private UuidInterface $id,
        #[ORM\Column(type: 'string', nullable: false)]
        private string $title,
        #[ORM\Column(type: 'string', nullable: false)]
        private string $slug,
        #[ORM\Column(type: 'string', nullable: false)]
        private string $content,
        #[ORM\Column(type: 'string', nullable: false)]
        private string $thumbnail,
        #[ORM\Column(type: 'string', nullable: false)]
        private string $author,
        #[ORM\Column(type: 'datetime_immutable', nullable: false)]
        private DateTimeImmutable $posted_at,
    ) {
        $this->categories = new ArrayCollection();
    }

    public function id(): UuidInterface
    {
        return $this->id;
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

    public function postedAt(): DateTimeImmutable
    {
        return $this->posted_at;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }
        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }
    public function toArray(): array
    {
        $categories = [];
        foreach ($this->getCategories() as $category) {
            $categories[] = $category->toArray();
        }

        return [
            'id' => $this->id(),
            'title' => $this->title(),
            'slug' => $this->slug(),
            'content' => $this->content(),
            'thumbnail' => $this->thumbnail(),
            'author' => $this->author(),
            'posted_at' => $this->postedAt()->format('Y-m-d H:i:s'),
            'categories' => $categories
        ];
    }
}
