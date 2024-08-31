<?php

declare(strict_types=1);

use App\Models\Like;
use App\Models\Post;

it('can delete like', function () {
    // setup the world
    $post = Post::factory()->create();

    // hit the store route
    login()
        ->delete(route('post.likes.destroy', $post))
        ->assertStatus(200);

    expect(Like::count())
        ->toBe(0);
});

it('cannot delete others likes', function () {
    // setup the world
    $post = Post::factory()->hasLikes(1)->create();

    // hit the store route
    login()
        ->delete(route('post.likes.destroy', $post))
        ->assertStatus(200);

    expect(Like::count())
        ->toBe(1);
});

it('cannot delete like for guest', function () {
    // setup the world
    $post = Post::factory()->hasLikes(1)->create();

    // hit the store route
    guest()
        ->delete(route('post.likes.destroy', $post))
        ->assertStatus(401);

    expect(Like::count())
        ->toBe(1);
});
