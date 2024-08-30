<?php

declare(strict_types=1);

use App\Models\Like;
use App\Models\Post;

it('can load the post likes', function () {
    // create posts
    $post = Post::factory()->create();
    $likes = Like::factory(5)->recycle($post)->create();

    // hit the index route
    $res = login()
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
    // create likes
    Like::factory(10)->create();

    // hit the index route
    guest()
        ->get(route('post.likes.index', 1))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);
});
