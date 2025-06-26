<?php

/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Service\CommentServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController.
 */
class CommentController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param CommentServiceInterface $commentService Comment service
     */
    public function __construct(private readonly CommentServiceInterface $commentService)
    {
    }

    /**
     * Delete action.
     *
     * @param Request                $request       HTTP request
     * @param Comment                $comment       Comment entity
     * @param EntityManagerInterface $entityManager Entity Manager
     *
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/comment/{id}/delete', name: 'comment_delete', methods: ['POST'])]
    #[IsGranted('COMMENT_DELETE', subject: 'comment')]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete-comment-'.$comment->getId(), $request->request->get('_token'))) {
            $this->commentService->delete($comment);
        }

        return $this->redirectToRoute('recipe_view', ['id' => $comment->getRecipe()->getId()]);
    }
}
