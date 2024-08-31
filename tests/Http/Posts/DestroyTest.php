<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;

it('can delete a post', function () {
    // setup the world
    $user = User::factory()->create();
    $post = Post::factory()->recycle($user)->create();

    // hit the delete route
    login($user)
        ->delete(route('posts.destroy', $post))
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $post->id,
                'body' => $post->body,
                'user' => [
                    'id' => $user->id,
                    'fullName' => $user->full_name,
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'username' => $user->username,
                ],
            ],
        ]);

    // posts table should be empty
    expect(Post::count())
        ->toBe(0);
});

test('cannot delete others posts', function () {
    // setup the world
    $post = Post::factory()->create();

    // hit the destroy route
    login()
        ->delete(route('posts.destroy', $post))
        ->assertStatus(403)
        ->assertJsonMissing(['data']);

    // posts table should be the same
    expect(Post::count())
        ->toBe(1);
});

test('cannot delete post for guest', function () {
    // setup the world
    $post = Post::factory()->create();

    // hit the delete route
    guest()
        ->delete(route('posts.destroy', $post))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);

    // posts table should look the same
    expect(Post::count())
        ->toBe(1);
});
