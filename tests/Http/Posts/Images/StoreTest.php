<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;

it('can add image to an existing post', function () {
    // setup the world
    $user = User::factory()->create();
    $post = Post::factory()->recycle($user)->create();

    // upload image
    $imageId = uploadImage($user);

    $requestBody = [
        'images' => [
            $imageId,
        ],
    ];

    // hit the destroy route
    login($user)
        ->post(route('post.images.store', $post), $requestBody)
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'url',
                ],
            ],
        ]);

    expect($post->refresh()->images()->count())
        ->toBe(1);
});

it('expects images to belong to the user', function () {
    // setup the world
    $user = User::factory()->create();
    $post = Post::factory()->recycle($user)->create();

    // upload image
    $imageId = uploadImage();

    $requestBody = [
        'images' => [
            $imageId,
        ],
    ];

    // hit the destroy route
    login($user)
        ->post(route('post.images.store', $post), $requestBody)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['images.0']);

    expect($post->refresh()->images()->count())
        ->toBe(0);
});

it('can add images to own posts only', function () {
    // setup the world
    $post = Post::factory()->create();

    // hit the destroy route
    login()
        ->post(route('post.images.store', $post))
        ->assertStatus(403);

    expect($post->refresh()->images()->count())
        ->toBe(0);
});

it('cannot add images for guests', function () {
    // setup the world
    $post = Post::factory()->create();

    // hit the destroy route
    guest()
        ->post(route('post.images.store', $post))
        ->assertStatus(401);

    expect($post->refresh()->images()->count())
        ->toBe(0);
});
