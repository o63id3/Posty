<?php

declare(strict_types=1);

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

it('can delete like', function () {
    // create user, post, and like
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $like = Like::factory()->recycle($user)->recycle($post)->create();

    // hit the store route
    login($user)
        ->delete(route('post.likes.destroy', $post))
        ->assertStatus(200);

    expect(Like::count())
        ->toBe(0);
});

it('cannot delete others likes', function () {
    // create post, and like
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $like = Like::factory()->recycle($user)->recycle($post)->create();

    // hit the store route
    login()
        ->delete(route('post.likes.destroy', $post))
        ->assertStatus(200);

    expect(Like::count())
        ->toBe(1);
});

it('cannot delete like for guest', function () {
    // create post, and like
    $post = Post::factory()->create();
    $like = Like::factory()->recycle($post)->create();

    // hit the store route
    guest()
        ->delete(route('post.likes.destroy', $post))
        ->assertStatus(401);

    expect(Like::count())
        ->toBe(1);
});
