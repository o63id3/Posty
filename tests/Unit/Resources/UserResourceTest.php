<?php

declare(strict_types=1);

use App\Http\Resources\UserResource;
use App\Models\User;

test('make', function () {
    $user = User::factory()->create();

    $resource = UserResource::make($user)->resolve();

    expect($resource)
        ->toHaveKey('id', $user->id)
        ->toHaveKey('fullName', $user->full_name)
        ->toHaveKey('firstName', $user->first_name)
        ->toHaveKey('lastName', $user->last_name)
        ->toHaveKey('createdAt', $user->created_at)
        ->toHaveKey('updatedAt', $user->updated_at);
});

test('make with authenticated user', function () {
    $user = User::factory()->create();

    login($user);
    $resource = UserResource::make($user)->resolve();
    expect($resource)
        ->toHaveKey('email', $user->email);

    login();
    $resource = UserResource::make($user)->resolve();
    expect($resource)
        ->not->toHaveKey('email', $user->email);
});

test('make with loaded data', function () {
    $user = User::factory()->create();

    $user->loadCount('ownPostsLikes');

    $resource = UserResource::make($user)->resolve();

    expect($resource)
        ->toHaveKey('likesCount', $user->posts()->count());
});
