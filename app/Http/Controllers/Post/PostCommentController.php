<?php

declare(strict_types=1);

namespace App\Http\Controllers\Post;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class PostCommentController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post): JsonResponse
    {
        $posts = $post
            ->posts()
            ->with(['user', 'images', 'parent:id,user_id', 'parent.user:id,first_name,last_name,username,avatar'])
            ->withCount(['likes', 'posts'])
            ->cursorPaginate();

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
