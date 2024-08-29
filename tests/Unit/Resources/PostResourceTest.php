<?php

use App\Models\Post;
use App\Http\Resources\PostResource;

test('make', function () {
    $post = Post::factory()->create();

    $resource = PostResource::make($post)->resolve();

    expect($resource)
        ->toHaveKey('id', $post->id)
        ->toHaveKey('body', $post->body)
        ->toHaveKey('createdAt', $post->created_at)
        ->toHaveKey('updatedAt', $post->updated_at);
});

test('make with loaded data', function () {
    $post = Post::factory()->create();

    $post->load('user');

    $resource = PostResource::make($post)->resolve();

    expect($resource)
        ->toHaveKey('user');
});
