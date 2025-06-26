<?php

/*
 * This file is part of the YourProject package.
 *
 * (c) Your Name <your.email@example.com>
 *
 * For license information, please view the LICENSE file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * @param ManagerRegistry $registry
     *                                  RecipeRepository constructor
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * Query all records.
     *
     * @param User $author User entity
     * @param Tag  $tagId  Tag ID
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(?User $author = null, ?int $tagId = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('recipe')
            ->select(
                'partial recipe.{id, createdAt, updatedAt, title}',
                'partial category.{id, title}',
                'partial tags.{id, title}',
                'AVG(rating.rating) AS avgRating'
            )
            ->join('recipe.category', 'category')
            ->leftJoin('recipe.tags', 'tags')
            ->leftJoin('recipe.ratings', 'rating')
            ->groupBy('recipe.id, category.id, tags.id')
            ->orderBy('avgRating', 'DESC');

        if ($author instanceof User) {
            $qb->andWhere('recipe.author = :author')
                ->setParameter('author', $author);
        }

        if ($tagId) {
            $qb->join('recipe.tags', 'filterTag')
                ->andWhere('filterTag.id = :tagId')
                ->setParameter('tagId', $tagId);
        }

        return $qb;
    }

    /**
     * Save entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function save(Recipe $recipe): void
    {
        $this->getEntityManager()->persist($recipe);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function delete(Recipe $recipe): void
    {
        $this->getEntityManager()->remove($recipe);
        $this->getEntityManager()->flush();
    }

    /**
     * Count recipe by category.
     *
     * @param Category $category Category
     *
     * @return int Number of recipes in category
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByCategory(Category $category): int
    {
        $qb = $this->CreateQueryBuilder('recipe');

        return (int) $qb
            ->select($qb->expr()->countDistinct('recipe.id'))
            ->where('recipe.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
