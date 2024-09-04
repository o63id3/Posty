<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;

it('can load the post posts index', function () {
    // setup the world
    $post = Post::factory()
        ->hasPosts(5)
        ->create();

    // hit the index route
    login()
        ->get(route('post.posts.index', $post))
        ->assertOk()
        ->assertJsonCount(5, 'data')
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
    $post = Post::factory()->create();
    Post::factory()->recycle($post)->recycle($blocker)->create();

    // hit the index route
    login($user)
        ->get(route('post.posts.index', $post))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('cannot load the posts index for guest', function () {
    // setup the world
    $post = Post::factory()
        ->hasPosts(5)
        ->create();

    // hit the index route
    guest()
        ->get(route('post.posts.index', $post))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);
});
