<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('SuperAdmin') || $user->hasRole('Admin');
    }

    /**
     * Determine if the user can view a specific user.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasRole('SuperAdmin') || $user->id === $model->id;
    }

    /**
     * Determine if the user can create a new user.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasRole('SuperAdmin') || $user->hasRole('Admin');
    }

    /**
     * Determine if the user can update a specific user.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasRole('SuperAdmin') || ($user->hasRole('Admin') && $model->hasRole('Patient'));
    }

    /**
     * Determine if the user can delete a specific user.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('SuperAdmin');
    }

    /**
     * Determine if the user can assign roles to other users.
     *
     * @param User $user
     * @return bool
     */
    public function assignRoles(User $user): bool
    {
        return $user->hasRole('SuperAdmin');
    }

    /**
     * Determine if the user can reset a password for a user.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function resetPassword(User $user, User $model): bool
    {
        return $user->hasRole('SuperAdmin') || $user->hasRole('Admin');
    }
}
