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
