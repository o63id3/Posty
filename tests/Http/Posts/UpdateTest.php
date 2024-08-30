<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

test('can update a post', function () {
    // create post
    $user = User::factory()->create();
    $post = Post::factory()->recycle($user)->create();

    $updatedPost = [
        'body' => 'This is my post body',
    ];

    // hit the update route
    login($user)
        ->put(route('posts.update', $post), $updatedPost)
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $post->id,
                'body' => $updatedPost['body'],
                'user' => [
                    'id' => $user->id,
                    'fullName' => $user->full_name,
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'username' => $user->username,
                ],
            ],
        ]);

    // post should be updated
    expect(Post::first())
        ->body->toBe($updatedPost['body'])
        ->user_id->toBe($user->id);
});

test('expects a valid body', function ($body) {
    // create post
    $user = User::factory()->create();
    $post = Post::factory()->recycle($user)->create();

    $updatedPost = [
        'body' => $body,
    ];

    // hit the update route
    login($user)
        ->put(route('posts.update', $post), $updatedPost)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['body']);

    // original post should look the same
    expect(Post::first())
        ->body->toBe($post->body);
})->with([
    null,
    '',
    1,
    'sm',
    Str::random(1001),
]);

test('cannot update others posts', function () {
    $post = Post::factory()->create();

    // hit the update route
    login()
        ->put(route('posts.update', $post))
        ->assertStatus(403)
        ->assertJsonMissing(['data']);

    // original post should look the same
    expect(Post::first())
        ->body->toBe($post->body)
        ->user_id->toBe($post->user->id);
});

test('cannot update post for guest', function () {
    $post = Post::factory()->create();

    // hit the update route
    guest()
        ->put(route('posts.update', $post))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);

    // original post should look the same
    expect(Post::first())
        ->body->toBe($post->body);
});
