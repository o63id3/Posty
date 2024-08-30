<?php

use App\Models\User;
use App\Models\Post;

test('user', function () {
    $user = User::factory()->create();
    $post = Post::factory()->recycle($user)->create();

    expect($post->user)
        ->toBe($user);
});

test('is owner', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $post = Post::factory()->recycle($userA)->create();

    expect($post->isOwner($userA))
        ->toBeTrue()
        ->and($post->isOwner($userB))
        ->toBeFalse();
});

test('parent', function () {
    $parent = Post::factory()->create();
    $post = Post::factory()->hasParent($parent)->create();

    expect($post->parent)
        ->toBe($parent);
});

test('posts', function () {
    $parent = Post::factory()->create();
    Post::factory(10)->hasParent($parent)->create();
    Post::factory(10)->create();

    expect($parent->posts->pluck('parent_id'))
        ->each->toBe($parent->id);
});