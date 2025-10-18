<?php

namespace App\Policies;

use App\Models\ReadingGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReadingGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create reading groups.
     * Any authenticated user can create.
     */
    public function create(User $user): bool
    {
        return true; // Since routes are under auth middleware
    }

    /**
     * Determine whether the user can update the reading group.
     * Only owner can update.
     */
    public function update(User $user, ReadingGroup $readingGroup): bool
    {
        return $user->id === $readingGroup->owner_id;
    }

    /**
     * Determine whether the user can delete the reading group.
     * Only owner can delete.
     */
    public function delete(User $user, ReadingGroup $readingGroup): bool
    {
        return $user->id === $readingGroup->owner_id;
    }

    /**
     * Determine whether the user can view the reading group.
     * Public groups can be viewed by anyone authenticated, private groups only if member/owner.
     */
    public function view(User $user, ReadingGroup $readingGroup): bool
    {
        // public groups can be viewed by anyone authenticated, private groups only if member/owner
        if (! $readingGroup->is_private) {
            return true;
        }

        // owner always can view
        if ($user->id === $readingGroup->owner_id) {
            return true;
        }

        // quick check membership (uses membership relationship)
        return $readingGroup->members()->where('user_id', $user->id)->exists();
    }
}