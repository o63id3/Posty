<?php

declare(strict_types=1);

use App\Models\User;

it('can show blocking list', function () {
    $user = User::factory()
        ->hasBlocking(1)
        ->create();

    login($user)
        ->get(route('blocking.index'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'fullName',
                    'firstName',
                    'lastName',
                    'username',
                    'avatar',
                    'isFollowing',
                    'createdAt',
                    'updatedAt',
                ],
            ],
            'meta' => [
                'hasMorePages',
            ],
            'links' => [
                'nextPageUrl',
            ],
        ]);
});

it('cannot show blocking list for guest', function () {
    User::factory()
        ->hasBlocking(1)
        ->create();

    guest()
        ->get(route('blocking.index'))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);
});
