<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;

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
                'postsCount' => $post->posts()->count(),
                'isLiked' => false,
                'createdAt' => $post->created_at,
                'updatedAt' => $post->updated_at,
                'user' => [
                    'id' => $post->user->id,
                    'fullName' => $post->user->fullName,
                    'firstName' => $post->user->first_name,
                    'lastName' => $post->user->last_name,
                    'username' => $post->user->username,
                    'avatar' => $post->user->avatar_url,
                    'isFollowing' => false,
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
                'postsCount' => $post->posts()->count(),
                'isLiked' => false,
                'createdAt' => $post->created_at,
                'updatedAt' => $post->updated_at,
                'user' => [
                    'id' => $post->user->id,
                    'fullName' => $post->user->fullName,
                    'firstName' => $post->user->first_name,
                    'lastName' => $post->user->last_name,
                    'username' => $post->user->username,
                    'avatar' => $post->user->avatar_url,
                    'isFollowing' => false,
                ],
                'parent' => [
                    'id' => $parent->id,
                    'isLiked' => false,
                    'user' => [
                        'id' => $parent->user->id,
                        'fullName' => $parent->user->fullName,
                        'firstName' => $parent->user->first_name,
                        'lastName' => $parent->user->last_name,
                        'username' => $parent->user->username,
                        'avatar' => $parent->user->avatar_url,
                        'isFollowing' => false,
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

it('cannot load the post if the owner is blocker', function () {
    // setup the world
    $user = User::factory()->create();
    $blocker = User::factory()->create();
    $blocker->blocking()->attach($user);
    $post = Post::factory()->recycle($blocker)->create();

    // hit the show route
    login($user)
        ->get(route('posts.show', $post))
        ->assertNotFound()
        ->assertJsonMissing(['data']);
});
