<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Resources\PostResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

final class UserPostController
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user): JsonResponse
    {
        $posts = $user
            ->posts()
            ->withCount('likes')
            ->cursorPaginate();

        foreach ($posts as $post) {
            $post->setRelation('user', $user);
        }

        return response()->json([
            'data' => PostResource::collection($posts),
            'meta' => [
                'hasMorePages' => $posts->hasMorePages(),
            ],
            'links' => [
                'nextPageUrl' => $posts->nextPageUrl(),
            ],
        ]);
    }
}
