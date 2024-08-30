<?php

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Str;

it('can create post', function () {
    // create post
    $user = User::factory()->create();

    $post = [
        'body' => 'This is my post body'
    ];

    // hit the store route
    login($user)
        ->post(route('posts.store'), $post)
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'body',
                'createdAt',
                'updatedAt',
                'user' => [
                    'id',
                    'fullName',
                    'firstName',
                    'lastName',
                    'username',
                ],
            ],
        ]);

    expect(Post::first())
        ->body->toBe($post['body'])
        ->user_id->toBe($user->id);
});

it('expects a valid body', function ($body) {
    $post = [
        'body' => $body
    ];

    // hit the store route
    login()
        ->post(route('posts.store', $post))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['body']);

    expect(Post::count())
        ->toBe(0);
})->with([
    null,
    '',
    1,
    'sm',
    Str::random(1001),
]);

it('cannot create post for guest', function () {
    // hit the store route
    guest()
        ->post(route('posts.store'))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);

    // posts table is empty
    expect(Post::count())
        ->toBe(0);
});

it('can store new post on other posts', function () {
    $post = Post::factory()->create();

    $postBody = [
        'body' => 'This is my post body'
    ];

    // hit the store route
    $response = login()
        ->post(route('posts.store', $post), $postBody)
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'parent' => [
                    'id',
                    'user' => [
                        'id',
                        'fullName',
                        'firstName',
                        'lastName',
                        'username',
                    ]
                ],
                'body',
                'createdAt',
                'updatedAt',
                'user' => [
                    'id',
                    'fullName',
                    'firstName',
                    'lastName',
                    'username',
                ],
            ],
        ]);

    expect(Post::find($response['data']['id']))
        ->parent_id->toBe($post->id)
        ->body->toBe($postBody['body']);
});
