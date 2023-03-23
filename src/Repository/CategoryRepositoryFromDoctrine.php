<?php

namespace ProjectApi\Repository;

use Doctrine\ORM\EntityManager;
use ProjectApi\Entity\Category;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class CategoryRepositoryFromDoctrine implements CategoryRepository
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    public function store(Category $category): void
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }

    public function read(mixed $args): Category
    {
        $resultId = $this
        ->entityManager
        ->getRepository(Category::class)
        ->findOneBy(['id' => Uuid::fromString($args['id'])]);

        if ($resultId === null) {
            throw new InvalidArgumentException('category ID not found');
        } else {
            return $resultId;
        }
    }
    public function getByIdString($id): Category
    {
        return $this
            ->entityManager
            ->getRepository(Category::class)
            ->findOneBy(['id' => $id]);
    }
    /**
     * @return Category[]
     */
    public function listAllCategories(): array
    {
        return $this
        ->entityManager
        ->getRepository(Category::class)
        ->findAll();
    }
    public function update(mixed $inputs, mixed $args): void
    {
        $category = $this->entityManager->createQueryBuilder();
        $query = $category->update('ProjectApi\Entity\Category', 'c')
            ->set('c.name', ':name')
            ->set('c.description', ':description')
            ->where('c.id = :id')
            ->setParameter('name', $inputs['name'])
            ->setParameter('description', $inputs['description'])
            ->setParameter('id', $args['id'])
            ->getQuery();
        $query->execute();
    }
    public function delete(mixed $args): void
    {
        $category = $this->entityManager->getReference('ProjectApi\Entity\Category', Uuid::fromString($args['id']));
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }
}
