<?php

use App\Models\User;
use App\Http\Resources\UserResource;

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

    $user->loadCount('posts');

    $resource = UserResource::make($user)->resolve();

    expect($resource)
        ->toHaveKey('postsCount', $user->posts()->count());
});
