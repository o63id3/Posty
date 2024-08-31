<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Collection;

it('can show user\'s followers', function () {
    // setup the world
    $users = User::factory(2)
        ->hasPosts(5)
        ->create();

    // choose the testing user
    $user = $users->random();

    // hit the show page
    $json = login()
        ->get(route('user.posts.index', $user))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'body',
                    'likesCount',
                    'createdAt',
                    'updatedAt',
                    'user' => [
                        'id',
                        'fullName',
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
        ])
        ->json();

    $userIds = (new Collection($json['data']))->pluck('user.id');
    expect($userIds)
        ->each->toBe($user->id);
});

it('cannot show user\'s followers for guest', function () {
    // setup the world
    $user = User::factory()
        ->hasPosts(5)
        ->create();

    // hit the show page
    $json = guest()
        ->get(route('user.posts.index', $user))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);
});
