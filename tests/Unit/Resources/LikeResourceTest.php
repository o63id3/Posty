<?php

declare(strict_types=1);

use App\Http\Resources\LikeResource;
use App\Models\Like;

test('make', function () {
    $like = Like::factory()->create();

    $resource = LikeResource::make($like)->resolve();

    expect($resource)
        ->toHaveLength(1)
        ->toHaveKey('id', $like->id);
});

test('make with user loaded', function () {
    $like = Like::factory()->create();

    $like->load('user');

    $resource = LikeResource::make($like)->resolve();

    expect($resource)
        ->toHaveLength(2)
        ->toHaveKey('user');
});

test('make with post loaded', function () {
    $like = Like::factory()->create();

    $like->load('post');

    $resource = LikeResource::make($like)->resolve();

    expect($resource)
        ->toHaveLength(2)
        ->toHaveKey('post');
});

test('make with post, and user loaded', function () {
    $like = Like::factory()->create();

    $like->load(['post', 'user']);

    $resource = LikeResource::make($like)->resolve();

    expect($resource)
        ->toHaveLength(3)
        ->toHaveKey('post');
});
