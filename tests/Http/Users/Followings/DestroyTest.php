<?php

declare(strict_types=1);

use App\Models\User;

it('can delete following', function () {
    // setup the world
    $user = User::factory()
        ->hasFollowing(5)
        ->create();

    // choose the testing user
    $target = $user->following->random();

    // hit the destroy route
    login($user)
        ->delete(route('user.following.destroy', $target))
        ->assertOk();

    expect($user->following()->count())
        ->toBe(4)
        ->and($user->following()->get()->pluck('id'))
        ->each->not->toBe($target->id);
});

it('cannot delete following for guest', function () {
    // setup the world
    $user = User::factory()
        ->hasFollowing(5)
        ->create();

    // choose the testing user
    $target = $user->following->random();

    // hit the destroy route
    guest()
        ->delete(route('user.following.destroy', $target))
        ->assertStatus(401);
});
