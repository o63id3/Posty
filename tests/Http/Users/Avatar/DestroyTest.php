<?php

declare(strict_types=1);

use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

it('can update the user avatar', function () {
    Storage::fake('images');
    Storage::fake('avatars');

    // setup the world
    $user = User::factory()->avatar()->create();

    // hit the upload route
    login($user)
        ->delete(route('user.avatar.destroy'))
        ->assertOk();

    // check fot the image
    expect($user->refresh())
        ->avatar->toBeNull();
});

it('cannot delete guest avatar', function () {
    Storage::fake('images');
    Storage::fake('avatars');

    // hit the upload route
    guest()
        ->delete(route('user.avatar.destroy'))
        ->assertStatus(401);
});
