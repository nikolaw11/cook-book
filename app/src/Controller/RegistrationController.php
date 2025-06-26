<?php

/**
 * Registration controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationForm;
use App\Service\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class RegistrationController.
 */
class RegistrationController extends AbstractController
{
    /**
     * Registration.
     *
     * @param Request                     $request            Request
     * @param UserPasswordHasherInterface $userPasswordHasher Password hasher
     * @param Security                    $security           Security
     * @param EntityManagerInterface      $entityManager      Entity manager
     * @param UserServiceInterface        $userService        User service
     *
     * @return Response the HTTP response
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, UserServiceInterface $userService): Response
    {
        if ($this->getUser() instanceof UserInterface) {
            return $this->redirectToRoute('recipe_index');
        }

        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /***** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $userService->registerUser($user, $plainPassword);

            $security->login($user, 'form_login', 'main');

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'registration/register.html.twig',
            [
                'registrationForm' => $form,
            ]
        );
    }
}
