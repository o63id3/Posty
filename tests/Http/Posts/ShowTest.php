<?php

declare(strict_types=1);

use App\Models\Post;

it('can load single post', function () {
    // setup the world
    $post = Post::factory()->hasImages(1)->create();

    // hit the show route
    login()
        ->get(route('posts.show', $post->id))
        ->assertOk()
        ->assertExactJson([
            'data' => [
                'id' => $post->id,
                'body' => $post->body,
                'likesCount' => $post->likes()->count(),
                'createdAt' => $post->created_at,
                'updatedAt' => $post->updated_at,
                'user' => [
                    'id' => $post->user->id,
                    'fullName' => $post->user->fullName,
                    'firstName' => $post->user->first_name,
                    'lastName' => $post->user->last_name,
                    'username' => $post->user->username,
                    'avatar' => $post->user->avatar_url,
                ],
                'parent' => null,
                'images.0' => [
                    'id' => $post->images[0]->id,
                    'url' => $post->images[0]->url,
                ],
            ],
        ]);
});

it('can load single post with parent', function () {
    // setup the world
    $parent = Post::factory()->create();
    $post = Post::factory()->hasParent($parent)->create();

    // hit the show route
    login()
        ->get(route('posts.show', $post->id))
        ->assertOk()
        ->assertExactJson([
            'data' => [
                'id' => $post->id,
                'body' => $post->body,
                'likesCount' => $post->likes()->count(),
                'createdAt' => $post->created_at,
                'updatedAt' => $post->updated_at,
                'user' => [
                    'id' => $post->user->id,
                    'fullName' => $post->user->fullName,
                    'firstName' => $post->user->first_name,
                    'lastName' => $post->user->last_name,
                    'username' => $post->user->username,
                    'avatar' => $post->user->avatar_url,
                ],
                'parent' => [
                    'id' => $parent->id,
                    'user' => [
                        'id' => $parent->user->id,
                        'fullName' => $parent->user->fullName,
                        'firstName' => $parent->user->first_name,
                        'lastName' => $parent->user->last_name,
                        'username' => $parent->user->username,
                        'avatar' => $parent->user->avatar_url,
                    ],
                ],
                'images' => [],
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
    // setup the world
    $post = Post::factory()->create();

    // hit the show route
    $response = guest()
        ->get(route('posts.show', $post))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);
});
