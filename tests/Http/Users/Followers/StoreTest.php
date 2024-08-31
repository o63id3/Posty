<?php

declare(strict_types=1);

use App\Models\User;

it('can follow others', function () {
    // setup the world
    $users = User::factory(2)->create();

    // choose the testing user
    $user = $users->first();
    $target = $users->last();

    // hit the store route
    login($user)
        ->post(route('user.followers.store', $target))
        ->assertOk();

    expect($user->followers()->count())
        ->toBe(1)
        ->and($user->followers()->get()->pluck('id'))
        ->each->toBe($target->id);
});

it('cannot follow self', function () {
    // setup the world
    $user = User::factory()->create();

    // hit the store route
    login($user)
        ->post(route('user.followers.store', $user))
        ->assertStatus(403);

    expect($user->followers()->count());
});

it('cannot follow others twice', function () {
    // setup the world
    $users = User::factory(2)->create();

    // choose the testing user
    $user = $users->first();
    $target = $users->last();

    // follow the target
    $user->following()->attach($target);

    // hit the store route
    login($user)
        ->post(route('user.followers.store', $target))
        ->assertOk();

    expect($user->followers()->count())
        ->toBe(1)
        ->and($user->followers()->get()->pluck('id'))
        ->each->toBe($target->id);
});

it('cannot follow others if guest', function () {
    // setup the world
    $target = User::factory()->create();

    // hit the store route
    guest()
        ->post(route('user.followers.store', $target))
        ->assertStatus(401);
});
