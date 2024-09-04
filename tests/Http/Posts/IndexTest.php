<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;

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
                    'postsCount',
                    'likesCount',
                    'isLiked',
                    'createdAt',
                    'updatedAt',
                    'user',
                    'images' => [
                        '*' => [
                            'id',
                            'url',
                        ],
                    ],
                    'parent' => [
                        'user' => [
                            'id',
                            'username',
                        ],
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

it('cannot load blockers posts', function () {
    // setup the world
    $user = User::factory()->create();
    $blocker = User::factory()->create();
    $blocker->blocking()->attach($user);
    Post::factory()->recycle($blocker)->create();

    // hit the index route
    login($user)
        ->get(route('posts.index'))
        ->assertOk()
        ->assertJsonCount(0, 'data');
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
