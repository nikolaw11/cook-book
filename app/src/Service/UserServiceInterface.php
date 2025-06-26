<?php

/**
 * User service interface.
 */

namespace App\Service;

use App\Entity\User;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Get list of all users.
     *
     * @return User[] Array of User entities
     */
    public function getAllUsers(): array;

    /**
     * Change user password.
     *
     * @param User   $user        User entity
     * @param string $newPassword The new plain password to be hashed and set
     */
    public function changeUserPassword(User $user, string $newPassword): void;

    /**
     * Register user.
     *
     * @param User   $user          User entity
     * @param string $plainPassword new password for the newly registered user
     */
    public function registerUser(User $user, string $plainPassword): void;
}
