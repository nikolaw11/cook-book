<?php

/**
 * Admin controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\AdminChangeUserPasswordType;
use App\Service\UserService;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Index action.
 *
 * @param int $page Page number
 *
 * @return Response HTTP response
 */
class AdminController extends AbstractController
{
    /**
     * User list.
     *
     * @param UserService $userService User service
     *
     * @return Response HTTP response
     */
    #[Route('/admin/users', name: 'admin_user_list')]
    public function userList(UserServiceInterface $userService): Response
    {
        $users = $userService->getAllUsers();

        return $this->render(
            'admin/user_list.html.twig',
            [
                'users' => $users,
            ]
        );
    }

    /**
     * Edit password.
     *
     * @param User                 $user        User
     * @param Request              $request     Request
     * @param UserServiceInterface $userService User service
     *
     * @return Response HTTP response with the form or redirection on success
     */
    #[Route('/admin/user/{id}/edit-password', name: 'admin_edit_user_password')]
    public function editUserPassword(User $user, Request $request, UserServiceInterface $userService): Response
    {
        $form = $this->createForm(AdminChangeUserPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('plainPassword')->getData();
            $userService->changeUserPassword($user, $newPassword);

            $this->addFlash('success', 'Hasło użytkownika zostało zmienione.');

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render(
            'admin/edit_user_password.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }
}
