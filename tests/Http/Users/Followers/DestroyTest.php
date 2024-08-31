<?php

declare(strict_types=1);

use App\Models\User;

it('can delete follower', function () {
    // setup the world
    $user = User::factory()
        ->hasFollowers(5)
        ->create();

    // choose the testing user
    $target = $user->followers->random();

    // hit the destroy route
    login($user)
        ->delete(route('user.followers.destroy', $target))
        ->assertOk();

    expect($user->followers()->count())
        ->toBe(4)
        ->and($user->followers()->get()->pluck('id'))
        ->each->not->toBe($target->id);
});

it('cannot delete follower for guest', function () {
    // setup the world
    $user = User::factory()
        ->hasFollowers(5)
        ->create();

    // choose the testing user
    $target = $user->followers->random();

    // hit the index route
    guest()
        ->delete(route('user.followers.destroy', $target))
        ->assertStatus(401);
});
