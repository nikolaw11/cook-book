<?php

/**
 * Recipe service.
 */

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class RecipeService.
 */
class RecipeService implements RecipeServiceInterface
{
    /**
     * Recipe repository.
     *
     * @var RecipeRepository
     */
    public $recipeRepository;
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param RecipeRepository       $repository    Recipe repository
     * @param PaginatorInterface     $paginator     Paginator
     * @param EntityManagerInterface $entityManager Entity manager
     */
    public function __construct(private readonly RecipeRepository $repository, private readonly PaginatorInterface $paginator, private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int  $page   Page number
     * @param User $author Author
     * @param Tag  $tagId  Tag ID
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page, ?User $author = null, ?int $tagId = null): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->repository->queryAll($author, $tagId),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => [
                    'recipe.id',
                    'recipe.createdAt',
                    'recipe.updatedAt',
                    'recipe.title',
                    'avgRating',
                    'category.title',
                ],
                'defaultSortFieldName' => 'avgRating',
                'defaultSortDirection' => 'desc',
            ]
        );
    }

    /**
     * Save entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function save(Recipe $recipe): void
    {
        $this->repository->save($recipe);
    }

    /**
     * Delete entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function delete(Recipe $recipe): void
    {
        $this->entityManager->remove($recipe);
        $this->entityManager->flush();
    }
}
