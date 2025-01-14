<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class UserPolicy
{
    /**
     * Determine whether the user can follow the user.
     */
    public function follow(User $user, User $target): bool
    {
        return $user->id !== $target->id;
    }

    /**
     * Determine whether the user can unfollow the user.
     */
    public function unfollow(User $user, User $target): bool
    {
        return $user->id !== $target->id;
    }

    /**
     * Determine whether the user can delete avatar.
     */
    public function deleteAvatar(User $user, User $target): bool
    {
        return $user->is($target);
    }
}
