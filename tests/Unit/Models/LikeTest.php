<?php

use App\Models\User;
use App\Models\Post;
use App\Models\Like;

test('user', function () {
    $user = User::factory()->create();
    $like = Like::factory()->recycle($user)->create();

    expect($like->user)
        ->toBe($user);
});

test('is owner', function () {
    $user = User::factory()->create();
    $likeA = Like::factory()->recycle($user)->create();
    $likeB = Like::factory()->create();

    expect($likeA->isOwner($user))
        ->toBeTrue()
        ->and($likeB->isOwner($user))
        ->toBeFalse();
});

test('post', function () {
    $post = Post::factory()->create();
    $like = Like::factory()->recycle($post)->create();

    expect($like->post)
        ->toBe($post);
});
