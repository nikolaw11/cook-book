<?php

/**
 * Recipe controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\Type\RecipeType;
use App\Repository\TagRepository;
use App\Security\Voter\RecipeVoter;
use App\Service\CommentService;
use App\Service\RecipeServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RecipeController.
 */
class RecipeController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param RecipeServiceInterface $recipeService Category service
     * @param TranslatorInterface    $translator    Translator
     * @param TagRepository          $tagRepository Tag repository
     */
    public function __construct(private readonly RecipeServiceInterface $recipeService, private readonly TranslatorInterface $translator, private readonly TagRepository $tagRepository)
    {
        $tags = $this->tagRepository->findAll();
    }

    /**
     * Index action.
     *
     * @param Request $request Request
     * @param int     $page    Page number
     *
     * @return Response HTTP response
     */
    #[Route(
        '/recipe',
        name: 'recipe_index',
        methods: 'GET'
    )]
    public function index(Request $request, #[MapQueryParameter] int $page = 1): Response
    {
        $tagId = $request->query->getInt('tag', 0) ?: null;

        $pagination = $this->recipeService->getPaginatedList($page, null, $tagId);

        $tags = $this->tagRepository->findAll();

        return $this->render(
            'recipe/index.html.twig',
            [
                'pagination' => $pagination,
                'tags' => $tags,
                'selectedTag' => $tagId,
            ]
        );
    }

    /**
     * View action.
     *
     * @param Request                $request        Request
     * @param Recipe                 $recipe         Recipe entity
     * @param EntityManagerInterface $entityManager  Entity Manger
     * @param CommentService         $commentService Comment service
     *
     * @return Response HTTP response
     */
    #[Route(
        '/recipe/{id}',
        name: 'recipe_view',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    #[IsGranted(RecipeVoter::VIEW, subject: 'recipe')]
    public function view(Request $request, Recipe $recipe, EntityManagerInterface $entityManager, CommentService $commentService): Response
    {
        $comment = new Comment();
        $comment->setRecipe($recipe);

        if ($this->getUser() instanceof \Symfony\Component\Security\Core\User\UserInterface) {
            $comment->setAuthor($this->getUser());
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentService->save($comment);

            return $this->redirectToRoute('recipe_view', ['id' => $recipe->getId()]);
        }

        $ratings = $recipe->getRatings();
        $count = count($ratings);
        $averageRating = 0;

        if ($count > 0) {
            $sum = array_reduce($ratings->toArray(), fn ($carry, $rating) => $carry + $rating->getRating(), 0);
            $averageRating = $sum / $count;
        }

        return $this->render(
            'recipe/view.html.twig',
            [
                'recipe' => $recipe,
                'commentForm' => $form->createView(),
                'comments' => $recipe->getComments(),
                'averageRating' => $averageRating,
            ]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/recipe/create',
        name: 'recipe_create',
        methods: 'GET|POST'
    )]
    public function create(Request $request): Response
    {
        /**** @var User $user */
        $user = $this->getUser();
        $recipe = new Recipe();
        $recipe->setAuthor($user);
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->save($recipe);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Recipe  $recipe  Recipe entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/recipe/{id}/edit',
        name: 'recipe_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
    public function edit(Request $request, Recipe $recipe): Response
    {
        $recipe->getTags()->count();

        $form = $this->createForm(
            RecipeType::class,
            $recipe,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('recipe_edit', ['id' => $recipe->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->save($recipe);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/edit.html.twig',
            [
                'form' => $form->createView(),
                'recipe' => $recipe,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Recipe  $recipe  Recipe entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/recipe/{id}/delete',
        name: 'recipe_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|DELETE'
    )]
    #[IsGranted(RecipeVoter::DELETE, subject: 'recipe')]
    public function delete(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(
            RecipeType::class,
            $recipe,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('recipe_delete', ['id' => $recipe->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->delete($recipe);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/delete.html.twig',
            [
                'form' => $form->createView(),
                'recipe' => $recipe,
            ]
        );
    }

    /**
     * Delete confirmed.
     *
     * @param Recipe                 $recipe Recipe
     * @param EntityManagerInterface $em     Entity Manager
     */
    #[Route('/recipe/{id}/delete-confirmed', name: 'recipe_delete_confirmed', methods: ['GET'])]
    public function deleteConfirmed(Recipe $recipe, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(RecipeVoter::DELETE, $recipe);

        $em->remove($recipe);
        $em->flush();

        $this->addFlash('success', 'Przepis został usunięty.');

        return $this->redirectToRoute('recipe_index');
    }
}
