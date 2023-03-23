<?php

namespace ProjectApi\Entity;

use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'categories')]

class Category
{
    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'categories')]
    private Collection $post;
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
        private UuidInterface $id,
        #[ORM\Column(name: 'name', type: 'string', nullable: false)]
        private string $name,
        #[ORM\Column(name: 'description', type: 'string', nullable: true)]
        private string $description,
    ) {
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }
    public function getPost(): Collection
    {
        return $this->post;
    }
    public function addPosts(Post $posts): self
    {
        if (!$this->post->contains($posts)) {
            $this->post->add($posts);
            $posts->addCategory($this);
        }

        return $this;
    }
    public function removeCategories(Post $posts): self
    {
        if ($this->post->removeElement($posts)) {
            $posts->removeCategory($this);
        }

        return $this;
    }
    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'description' => $this->description(),
        ];
    }
}
