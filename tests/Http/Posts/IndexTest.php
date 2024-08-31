<?php

declare(strict_types=1);

use App\Models\Post;

it('can load the posts index', function () {
    // setup the world
    Post::factory(10)->create();

    // hit the index route
    login()
        ->get(route('posts.index'))
        ->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'body',
                    'likesCount',
                    'createdAt',
                    'updatedAt',
                    'user',
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

it('cannot load the posts index for guest', function () {
    // setup the world
    Post::factory(10)->create();

    // hit the index route
    guest()
        ->get(route('posts.index'))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);
});
