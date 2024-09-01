<?php

declare(strict_types=1);

namespace App\Http\Controllers\Post;

use App\Http\Resources\PostResource;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Query\Builder;
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
            ->with('user')
            ->withCount('likes')
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
            'images' => ['array'],
            'images.*' => Rule::forEach(function (?string $value, string $attribute) {
                return [
                    Rule::exists('images', 'id')->where(function (Builder $query) {
                        return $query->where('user_id', auth()->id());
                    }),
                ];
            }),
        ]);

        $user = type($request->user())->as(User::class);

        $post = $user
            ->posts()
            ->create([
                'parent_id' => $parent?->id,
                'body' => $request->body,
            ]);

        if (array_key_exists('images', $validated)) {
            $images = Image::whereIn('id', $request->images)->get();

            foreach ($images as $image) {
                $postImages[] = $post
                    ->images()
                    ->create([
                        'path' => $image->path,
                        'size' => $image->size,
                    ]);
            }

            $post->setRelation('images', $postImages);

        }

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
        $post->load('user:id,first_name,last_name,username,avatar');
        $post->loadCount('likes');

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
