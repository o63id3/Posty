<?php

declare(strict_types=1);

namespace App\Http\Controllers\Post;

use App\Actions\NewPost;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class PostController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = Post::query()
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
    public function store(Request $request, Post $parent, NewPost $action): JsonResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'min:5', 'max:1000'],
            'images' => ['array'],
            'images.*' => Rule::forEach(fn () => Rule::exists('images', 'id')->where('user_id', auth()->id())),
        ]);

        $user = type($request->user())->as(User::class);
        $post = $action->handle($user, $validated, $parent);

        return response()->json([
            'data' => PostResource::make($post),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): JsonResponse
    {
        $post->load(['user:id,first_name,last_name,username,avatar', 'images', 'parent:id,user_id', 'parent.user:id,first_name,last_name,username,avatar']);
        $post->loadCount(['likes', 'posts']);

        return response()->json([
            'data' => PostResource::make($post),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post): JsonResponse
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
    public function destroy(Request $request, Post $post): JsonResponse
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
