<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;

it('can delete image from an existing post', function () {
    // setup the world
    $user = User::factory()->create();
    $post = Post::factory()->hasImages(1)->recycle($user)->create();

    // hit the destroy route
    login($user)
        ->delete(route('post.images.destroy', [$post, $post->images->first()]))
        ->assertOk();

    expect($post->refresh()->images()->count())
        ->toBe(0);
});

it('can delete image from own posts only', function () {
    // setup the world
    $post = Post::factory()->hasImages(1)->create();

    // hit the destroy route
    login()
        ->delete(route('post.images.destroy', [$post, $post->images->first()]))
        ->assertStatus(403);

    expect($post->refresh()->images()->count())
        ->toBe(1);
});

it('cannot delete images for guests', function () {
    // setup the world
    $post = Post::factory()->hasImages(1)->create();

    // hit the destroy route
    guest()
        ->delete(route('post.images.destroy', [$post, $post->images->first()]))
        ->assertStatus(401);

    expect($post->refresh()->images()->count())
        ->toBe(1);
});
