<?php

declare(strict_types=1);

use App\Models\User;

test('follow', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    expect($userA->can('follow', $userB))
        ->toBeTrue()
        ->and($userA->can('follow', $userA))
        ->toBeFalse();
});

test('unfollow', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    expect($userA->can('unfollow', $userB))
        ->toBeTrue()
        ->and($userA->can('unfollow', $userA))
        ->toBeFalse();
});

test('delete avatar', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    expect($userA->can('deleteAvatar', $userA))
        ->toBeTrue()
        ->and($userA->can('deleteAvatar', $userB))
        ->toBeFalse();
});
