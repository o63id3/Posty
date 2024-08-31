<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fullName' => $this->full_name,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'username' => $this->username,
            'avatar' => $this->avatar_url,
            'createdAt' => $this->whenHas('created_at'),
            'updatedAt' => $this->whenHas('updated_at'),

            'email' => $this->when(auth()->id() === $this->id, fn () => $this->whenHas('email')),

            'postsCount' => $this->whenCounted('posts', fn () => $this->posts_count),
            'likesCount' => $this->whenCounted('ownPostsLikes', fn () => $this->own_posts_likes_count),
            'followingCount' => $this->whenCounted('following', fn () => $this->following_count),
            'followersCount' => $this->whenCounted('followers', fn () => $this->followers_count),
        ];
    }
}
