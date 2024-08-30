<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class PostResource extends JsonResource
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
            'body' => $this->whenHas('body'),
            'createdAt' => $this->whenHas('created_at'),
            'updatedAt' => $this->whenHas('updated_at'),

            'user' => $this->whenLoaded('user', fn () => UserResource::make($this->user)),

            'parent' => $this->whenLoaded('parent', fn () => PostResource::make($this->parent)),

            'likesCount' => $this->whenCounted('likes', fn () => $this->likes_count),
        ];
    }
}
