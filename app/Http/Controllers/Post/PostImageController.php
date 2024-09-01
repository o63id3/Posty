<?php

declare(strict_types=1);

namespace App\Http\Controllers\Post;

use App\Http\Resources\PostImageResource;
use App\Models\Image;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class PostImageController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post): JsonResponse
    {
        Gate::authorize('addImages', $post);

        $validated = $request->validate([
            'images' => ['required', 'array'],
            'images.*' => Rule::forEach(function (?string $value, string $attribute) {
                return [
                    Rule::exists('images', 'id')->where(function (Builder $query) {
                        return $query->where('user_id', auth()->id());
                    }),
                ];
            }),
        ]);

        $images = Image::whereIn('id', $validated['images'])->get();

        $postImages = [];
        foreach ($images as $image) {
            $postImages[] = $post
                ->images()
                ->create([
                    'path' => $image->path,
                    'size' => $image->size,
                ]);
        }

        return response()->json([
            'data' => PostImageResource::collection($postImages),
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, PostImage $image): JsonResponse
    {
        Gate::authorize('deleteImages', $post);

        $post
            ->images()
            ->where('id', $image->id)
            ->delete();

        return response()->json();
    }
}
