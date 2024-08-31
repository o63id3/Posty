<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Collection;

it('can show followers for single user', function () {
    // setup the world
    $users = User::factory(2)
        ->hasFollowers(5)
        ->create();

    // choose the testing user
    $user = $users->random();

    // hit the index route
    $json = login()
        ->get(route('user.followers.index', $user))
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
        ->each->toBeIn($user->followers->pluck('id'));
});

it('cannot show followers for single user for guest', function () {
    // setup the world
    $users = User::factory(2)
        ->hasFollowers(5)
        ->create();

    // choose the testing user
    $user = $users->random();

    // hit the index route
    guest()
        ->get(route('user.followers.index', $user))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);
});
