<?php

declare(strict_types=1);

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

it('can create like', function () {
    // setup the world
    $user = User::factory()->create();
    $post = Post::factory(5)->create()->random();

    // hit the store route
    login($user)
        ->post(route('post.likes.store', $post))
        ->assertStatus(201);

    expect(Like::first())
        ->user->toBe($user)
        ->post->toBe($post);
});

it('cannot create like for guest', function () {
    // setup the world
    $post = Post::factory(5)->create()->random();

    // hit the store route
    guest()
        ->post(route('post.likes.store', $post))
        ->assertStatus(401);

    // likes table is empty
    expect(Like::count())
        ->toBe(0);
});

it('cannot create like for non-existing post', function () {
    // hit the store route
    login()
        ->post(route('post.likes.store', 100))
        ->assertNotFound();

    // likes table is empty
    expect(Like::count())
        ->toBe(0);
});
