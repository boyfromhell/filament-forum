<?php

namespace IchBin\FilamentForum\Policies;

use App\Models\User;
use IchBin\FilamentForum\Models\Discussion;

class DiscussionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Discussion $discussion): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Discussion $discussion = null): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->hasVerifiedEmail()) {
            return true;
        }

        return false;
        //return $user->can('create_discussion');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Discussion $discussion): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Discussion $discussion): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Discussion $discussion): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Discussion $discussion): bool
    {
        //
    }
}
