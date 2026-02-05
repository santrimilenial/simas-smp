<?php

namespace App\Policies;

use App\Models\TeachingLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeachingLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isGuru() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TeachingLog $teachingLog): bool
    {
        return $user->isAdmin() || $user->id === $teachingLog->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isGuru();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TeachingLog $teachingLog): bool
    {
        return $user->id === $teachingLog->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TeachingLog $teachingLog): bool
    {
        return $user->id === $teachingLog->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TeachingLog $teachingLog): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TeachingLog $teachingLog): bool
    {
        return $user->isAdmin();
    }
}
