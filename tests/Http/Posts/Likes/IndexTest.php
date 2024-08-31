<?php

declare(strict_types=1);

use App\Models\Post;

it('can load the post likes', function () {
    // setup the world
    $post = Post::factory()->hasLikes(5)->create();

    // hit the index route
    login()
        ->get(route('post.likes.index', $post))
        ->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user' => [
                        'id',
                        'fullName',
                        'firstName',
                        'lastName',
                        'username',
                    ],
                ],
            ],
            'meta' => [
                'hasMorePages',
            ],
            'links' => [
                'nextPageUrl',
            ],
        ]);
});

it('cannot load the post likes guest', function () {
    // setup the world
    $post = Post::factory()->hasLikes(5)->create();

    // hit the index route
    guest()
        ->get(route('post.likes.index', $post))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);
});
