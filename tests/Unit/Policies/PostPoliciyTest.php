<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;

test('update', function () {
    $user = User::factory()->create();
    $postA = Post::factory()->recycle($user)->create();
    $postB = Post::factory()->create();

    expect($user->can('update', $postA))
        ->toBeTrue()
        ->and($user->can('update', $postB))
        ->toBeFalse();
});

test('delete', function () {
    $user = User::factory()->create();
    $postA = Post::factory()->recycle($user)->create();
    $postB = Post::factory()->create();

    expect($user->can('delete', $postA))
        ->toBeTrue()
        ->and($user->can('delete', $postB))
        ->toBeFalse();
});
