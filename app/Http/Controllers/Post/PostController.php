<?php

declare(strict_types=1);

namespace App\Http\Controllers\Post;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

final class PostController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = Post::query()
            ->with('user')
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
    public function store(Request $request, Post $parent)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $user = type($request->user())->as(User::class);

        $post = $user
            ->posts()
            ->create([
                'parent_id' => $parent?->id,
                'body' => $request->body,
            ]);

        if ($parent) {
            $parent->load('user');
            $post->setRelation('parent', $parent);
        }

        $post->setRelation('user', $user);

        return response()->json([
            'data' => PostResource::make($post),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load('user:id,first_name,last_name,username');

        return response()->json([
            'data' => PostResource::make($post),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $post->update([
            'body' => $request->body,
        ]);

        $user = type($request->user())->as(User::class);
        $post->setRelation('user', $user);

        return response()->json([
            'data' => PostResource::make($post),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();

        $user = type($request->user())->as(User::class);
        $post->setRelation('user', $user);

        return response()->json([
            'data' => PostResource::make($post),
        ]);
    }
}
