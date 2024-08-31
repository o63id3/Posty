<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Collection;

it('can show user\'s following', function () {
    // setup the world
    $users = User::factory(2)
        ->hasFollowing(5)
        ->create();

    // choose the testing user
    $user = $users->random();

    // hit the index route
    $json = login()
        ->get(route('user.following.index', $user))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'fullName',
                    'firstName',
                    'lastName',
                    'username',
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

    $userIds = (new Collection($json['data']))->pluck('id');
    expect($userIds)
        ->each->toBeIn($user->following->pluck('id'));
});

it('cannot show user\'s following for guest', function () {
    // setup the world
    $users = User::factory(2)
        ->hasFollowing(5)
        ->create();

    // choose the testing user
    $user = $users->random();

    // hit the index route
    guest()
        ->get(route('user.following.index', $user))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);
});
