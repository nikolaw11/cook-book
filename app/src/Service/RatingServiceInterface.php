<?php

/**
 * Rating service interface.
 */

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\User;

/**
 * Interface RatingServiceInterface.
 */
interface RatingServiceInterface
{
    /**
     * Rates a recipe by a user.
     *
     * @param User   $user        User entity
     * @param Recipe $recipe      Recipe entity
     * @param int    $ratingValue Rating value (1–5)
     */
    public function rateRecipe(User $user, Recipe $recipe, int $ratingValue): void;
}
