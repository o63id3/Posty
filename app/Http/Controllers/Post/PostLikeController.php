<?php

declare(strict_types=1);

namespace App\Http\Controllers\Post;

use App\Http\Resources\LikeResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class PostLikeController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post): JsonResponse
    {
        $likes = $post
            ->likes()
            ->with('user')
            ->cursorPaginate();

        return response()->json([
            'data' => LikeResource::collection($likes),
            'meta' => [
                'hasMorePages' => $likes->hasMorePages(),
            ],
            'links' => [
                'nextPageUrl' => $likes->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post): JsonResponse
    {
        $request
            ->user()
            ->likes()
            ->create([
                'post_id' => $post->id,
            ]);

        return response()->json(status: 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post): JsonResponse
    {
        $request
            ->user()
            ->likes()
            ->where('post_id', $post->id)
            ->delete();

        return response()->json();
    }
}
