<?php

declare(strict_types=1);

use App\Http\Resources\PostResource;
use App\Models\Post;

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

test('make with loaded parent', function () {
    $post = Post::factory()->hasParent()->create();

    $post->load('parent:id,user_id');
    $post->load('parent.user:id,first_name,last_name,username');

    $resource = PostResource::make($post)->resolve();

    expect($resource)
        ->toHaveKey('parent');
});

test('make with counted likes', function () {
    $post = Post::factory()->hasParent()->create();

    $post->loadCount('likes');

    $resource = PostResource::make($post)->resolve();

    expect($resource)
        ->toHaveKey('likesCount');
});

test('make with loaded images', function () {
    $post = Post::factory()->hasImages(2)->create();

    $post->load('images');

    $resource = PostResource::make($post)->resolve();

    expect($resource)
        ->toHaveKey('images');
});

test('make with counted posts', function () {
    $post = Post::factory()->hasParent()->create();

    $post->loadCount('posts');

    $resource = PostResource::make($post)->resolve();

    expect($resource)
        ->toHaveKey('postsCount');
});

test('make with is liked', function () {
    $post = Post::factory()->create();

    login();

    $resource = PostResource::make($post)->resolve();

    expect($resource)
        ->toHaveKey('isLiked', false);
});
