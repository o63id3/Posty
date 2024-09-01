<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Image;
use App\Models\User;

final class ImagePolicy
{
    /**
     * Determine whether the user can set the model as avatar.
     */
    public function setAsAvatar(User $user, Image $image): bool
    {
        return $user->id === $image->user_id;
    }

    /**
     * Determine whether the user can add the model to his post.
     */
    public function addToPost(User $user, Image $image): bool
    {
        return $user->id === $image->user_id;
    }
}
