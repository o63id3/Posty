<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

it('can create post', function () {
    // setup the world
    $user = User::factory()->create();

    // build the request body
    $requestBody = [
        'body' => 'This is my post body',
    ];

    // hit the store route
    login($user)
        ->post(route('posts.store'), $requestBody)
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
        ->body->toBe($requestBody['body'])
        ->user->toBe($user);
});

it('can create post with images', function () {
    // setup the world
    $user = User::factory()->create();

    // upload images
    $image1Id = uploadImage($user);
    $image2Id = uploadImage($user);

    // build the request body
    $requestBody = [
        'body' => 'This is my post body',
        'images' => [
            $image1Id,
            $image2Id,
        ],
    ];

    // hit the store route
    login($user)
        ->post(route('posts.store'), $requestBody)
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'body',
                'images' => [
                    '*' => [
                        'id',
                        'url',
                    ],
                ],
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

    expect($post = Post::first())
        ->body->toBe($requestBody['body'])
        ->user->toBe($user)
        ->and($post->images()->count())
        ->toBe(2);
});

it('expects images to belong to the user', function () {
    // upload images
    $imageId = uploadImage();

    // build the request body
    $requestBody = [
        'body' => 'This is my post body',
        'images' => [
            $imageId,
        ],
    ];

    // hit the store route
    login()
        ->post(route('posts.store'), $requestBody)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['images.0']);

    expect(Post::count())
        ->toBe(0);
});

it('expects a valid body', function ($body) {
    // build the request body
    $requestBody = [
        'body' => $body,
    ];

    // hit the store route
    login()
        ->post(route('posts.store', $requestBody))
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

    // posts table should be empty
    expect(Post::count())
        ->toBe(0);
});

it('can store new post on other posts', function () {
    // setup the world
    $post = Post::factory()->create();

    // build the request body
    $requestBody = [
        'body' => 'This is my post body',
    ];

    // hit the store route
    $response = login()
        ->post(route('posts.store', $post), $requestBody)
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
                    ],
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
        ->parent->toBe($post)
        ->body->toBe($requestBody['body']);
});
