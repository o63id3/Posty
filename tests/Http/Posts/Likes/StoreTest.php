<?php

use App\Models\User;
use App\Models\Post;
use App\Models\Like;

it('can create like', function () {
    // create user and posts
    $user = User::factory()->create();
    $post = Post::factory(5)->create()->random();

    // hit the store route
    login($user)
        ->post(route('post.likes.store', $post))
        ->assertStatus(201);

    expect(Like::first())
        ->user_id->toBe($user->id)
        ->post_id->toBe($post->id);
});

it('cannot create like for guest', function () {
    // create posts
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
