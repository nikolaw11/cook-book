<?php

/**
 * Rating service.
 */

namespace App\Service;

use App\Entity\Rating;
use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class RatingService.
 */
class RatingService implements RatingServiceInterface
{
    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager    Entity manager
     * @param RatingRepository       $ratingRepository Rating repository
     */
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly RatingRepository $ratingRepository)
    {
    }

    /**
     * Rates a recipe by a user.
     *
     * @param User   $user        User entity
     * @param Recipe $recipe      Recipe entity
     * @param int    $ratingValue Rating value (1–5)
     */
    public function rateRecipe(User $user, Recipe $recipe, int $ratingValue): void
    {
        $rating = $this->ratingRepository->findOneBy(
            [
                'recipe' => $recipe,
                'user' => $user,
            ]
        );

        if (null === $rating) {
            $rating = new Rating();
            $rating->setRecipe($recipe);
            $rating->setUser($user);
            $this->entityManager->persist($rating);
        }

        $rating->setRating($ratingValue);
        $this->entityManager->flush();
    }
}
