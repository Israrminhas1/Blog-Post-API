<?php

namespace ProjectApi\Repository;

use ProjectApi\Controller\FileController;
use ProjectApi\Entity\Post;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class PostRepositoryFromDoctrine implements PostRepository
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    public function store(Post $post): void
    {
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    public function listAllPosts(): array
    {
        return $this
        ->entityManager
        ->getRepository(Post::class)
        ->findAll();
    }

    public function read(mixed $args): Post
    {
        $resultId =  $this
            ->entityManager
            ->getRepository(Post::class)
            ->findOneBy(['id' => Uuid::fromString($args['id'])]);

            return $resultId;
    }

    public function readBySlug(mixed $args): Post
    {
        $result =  $this
            ->entityManager
            ->getRepository(Post::class)
            ->findOneBy(['slug' => $args['slug']]);

            return $result;
    }
    public function update(mixed $inputs, mixed $args): void
    {
        $post = $this->entityManager->createQueryBuilder();
        $query = $post->update('ProjectApi\Entity\Post', 'p')
            ->set('p.title', ':title')
            ->set('p.slug', ':slug')
            ->set('p.content', ':content')
            ->set('p.thumbnail', ':thumbnail')
            ->set('p.author', ':author')
            ->set('p.posted_at', ':posted_at')
            ->where('p.id = :id')
            ->setParameter('title', $inputs['title'])
            ->setParameter('slug', $inputs['slug'])
            ->setParameter('content', $inputs['content'])
            ->setParameter('thumbnail', $inputs['thumbnail'])
            ->setParameter('author', $inputs['author'])
            ->setParameter('posted_at', new DateTimeImmutable())
            ->setParameter('id', $args['id'])
            ->getQuery();
        $query->execute();
    }
    public function delete(mixed $args): void
    {
        $post = $this->entityManager->getReference('ProjectApi\Entity\Post', Uuid::fromString($args['id']));
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }
}
