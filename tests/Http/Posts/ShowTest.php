<?php

declare(strict_types=1);

use App\Models\Post;

it('can load single post', function () {
    // create post
    $post = Post::factory()->create();

    // hit the show route
    login()
        ->get(route('posts.show', $post->id))
        ->assertOk()
        ->assertExactJson([
            'data' => [
                'id' => $post->id,
                'body' => $post->body,
                'createdAt' => $post->created_at,
                'updatedAt' => $post->updated_at,
                'user' => [
                    'id' => $post->user->id,
                    'fullName' => $post->user->fullName,
                    'firstName' => $post->user->first_name,
                    'lastName' => $post->user->last_name,
                    'username' => $post->user->username,
                ],
            ],
        ]);
});

it('responds with 404', function () {
    // hit the show route
    $response = login()
        ->get(route('posts.show', 1))
        ->assertNotFound()
        ->assertJsonMissing(['data']);
});

it('cannot load the posts index for guest', function () {
    // hit the show route
    $response = guest()
        ->get(route('posts.show', Post::factory()->create()))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);
});
