<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

test('can update a post', function () {
    // setup the world
    $user = User::factory()->create();
    $post = Post::factory()->recycle($user)->create();

    // build the request body
    $requestBody = [
        'body' => 'This is my post body',
    ];

    // hit the update route
    login($user)
        ->put(route('posts.update', $post), $requestBody)
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $post->id,
                'body' => $requestBody['body'],
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
    expect($post->refresh())
        ->body->toBe($requestBody['body'])
        ->user->toBe($user);
});

test('expects a valid body', function ($body) {
    // setup the world
    $user = User::factory()->create();
    $post = Post::factory()->recycle($user)->create();

    // build the request body
    $requestBody = [
        'body' => $body,
    ];

    // hit the update route
    login($user)
        ->put(route('posts.update', $post), $requestBody)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['body']);

    // original post should look the same
    expect($post->refresh())
        ->body->not->toBe($requestBody['body']);
})->with([
    null,
    '',
    1,
    'sm',
    Str::random(1001),
]);

test('cannot update others posts', function () {
    // setup the world
    $post = Post::factory()->create();

    // hit the update route
    login()
        ->put(route('posts.update', $post))
        ->assertStatus(403)
        ->assertJsonMissing(['data']);

    // original post should look the same
    expect(Post::first())
        ->body->toBe($post->body);
});

test('cannot update post for guest', function () {
    // setup the world
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
