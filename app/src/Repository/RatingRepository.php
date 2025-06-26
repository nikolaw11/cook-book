<?php

/*
 * This file is part of the YourProject package.
 *
 * (c) Your Name <your.email@example.com>
 *
 * For license information, please view the LICENSE file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Rating;
use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rating>
 */
class RatingRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    /**
     * Get average rating.
     *
     * @param Recipe $recipe Recipe
     *
     * @return float
     */
    public function getAverageRating(Recipe $recipe): float
    {
        return (float) $this->createQueryBuilder('r')
            ->select('AVG(r.rating)')
            ->where('r.recipe = :recipe')
            ->setParameter('recipe', $recipe)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Rating[] Returns an array of Rating objects
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

    //    public function findOneBySomeField($value): ?Rating
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
