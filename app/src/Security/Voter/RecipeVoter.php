<?php

/**
 * Recipe voter.
 */

namespace App\Security\Voter;

use App\Entity\Recipe;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class RecipeVoter.
 */
final class RecipeVoter extends Voter
{
    /**
     * Delete permission.
     *
     * @const string
     */
    public const DELETE = 'RECIPE_DELETE';

    /**
     * Edit permission.
     *
     * @const string
     */
    public const EDIT = 'RECIPE_EDIT';

    /**
     * View permission.
     *
     * @const string
     */
    public const VIEW = 'RECIPE_VIEW';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool Result
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::DELETE, self::EDIT, self::VIEW])
            && $subject instanceof Recipe;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute Permission name
     * @param mixed          $subject   Object
     * @param TokenInterface $token     Security token
     *
     * @return bool Vote result
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (!$subject instanceof Recipe) {
            return false;
        }

        $user = $token->getUser();

        return match ($attribute) {
            self::VIEW => true, // allow everyone to view

            self::EDIT => (
                $user instanceof UserInterface
                && $this->canEdit($subject, $user)
            ),

            self::DELETE => (
                $user instanceof UserInterface
                && $this->canDelete($subject, $user)
            ),

            default => false,
        };
    }

    /**
     * Checks if user can delete task.
     *
     * @param Recipe        $recipe Task entity
     * @param UserInterface $user   User
     *
     * @return bool Result
     */
    private function canDelete(Recipe $recipe, UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles(), true) || $recipe->getAuthor() === $user;
    }

    /**
     * Checks if user can edit task.
     *
     * @param Recipe        $recipe Task entity
     * @param UserInterface $user   User
     *
     * @return bool Result
     */
    private function canEdit(Recipe $recipe, UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles(), true) || $recipe->getAuthor() === $user;
    }

    /**
     * Checks if user can view task.
     *
     * @param Recipe        $recipe Task entity
     * @param UserInterface $user   User
     *
     * @return bool Result
     */
    private function canView(Recipe $recipe, UserInterface $user): bool
    {
        return true;
    }
}
