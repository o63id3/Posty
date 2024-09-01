<?php

declare(strict_types=1);

use App\Models\Image;
use App\Models\User;

test('set as avatar', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $image = Image::factory()->recycle($userA)->create();

    expect($userA->can('setAsAvatar', $image))
        ->toBeTrue()
        ->and($userB->can('setAsAvatar', $image))
        ->toBeFalse();
});

test('add to post', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $image = Image::factory()->recycle($userA)->create();

    expect($userA->can('addToPost', $image))
        ->toBeTrue()
        ->and($userB->can('addToPost', $image))
        ->toBeFalse();
});
