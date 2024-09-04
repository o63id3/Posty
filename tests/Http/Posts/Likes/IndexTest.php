<?php

declare(strict_types=1);

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

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

it('cannot load blockers likes', function () {
    // setup the world
    $user = User::factory()->create();
    $blocker = User::factory()->create();
    $blocker->blocking()->attach($user);
    $post = Post::factory()->create();
    Like::factory()->recycle($post)->recycle($blocker)->create();

    // hit the index route
    login($user)
        ->get(route('post.posts.index', $post))
        ->assertOk()
        ->assertJsonCount(0, 'data');
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
