<?php

/**
 * Rating controller.
 */

namespace App\Controller;

use App\Entity\Recipe;
use App\Service\RatingServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RatingController.
 */
class RatingController extends AbstractController
{
    /**
     * Rate action.
     *
     * @param Request                $request       HTTP request
     * @param EntityManagerInterface $em            Entity manager
     * @param RatingServiceInterface $ratingService Rating service
     *
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/rate', name: 'rate_recipe', methods: ['POST'])]
    public function rate(Request $request, EntityManagerInterface $em, RatingServiceInterface $ratingService): Response
    {
        $user = $this->getUser();
        if (!$user instanceof \Symfony\Component\Security\Core\User\UserInterface) {
            throw $this->createAccessDeniedException('You must be logged in to rate.');
        }

        $recipeId = $request->request->get('recipe_id');
        $ratingValue = (int) $request->request->get('rating');

        if ($ratingValue < 1 || $ratingValue > 5) {
            return $this->render(
                'recipe/view.html.twig',
                [
                    'recipe' => $em->getRepository(Recipe::class)->find($recipeId),
                    'error' => 'Invalid rating value.',
                ]
            );
        }

        $recipe = $em->getRepository(Recipe::class)->find($recipeId);
        if (null === $recipe) {
            throw $this->createNotFoundException('Recipe not found.');
        }

        $ratingService->rateRecipe($user, $recipe, $ratingValue);

        return $this->redirectToRoute('recipe_view', ['id' => $recipe->getId()]);
    }
}
