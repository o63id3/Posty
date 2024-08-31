<?php

declare(strict_types=1);

use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

it('can update the user avatar', function () {
    Storage::fake('images');
    Storage::fake('avatars');

    // setup the world
    $user = User::factory()->create();

    // upload the image first
    $imageId = uploadImage($user);
    $image = Image::find($imageId);

    // hit the upload route
    login($user)
        ->put(route('user.avatar.update'), [
            'avatar' => $imageId,
        ])
        ->assertOk();

    // check fot the image
    expect($user->refresh())
        ->avatar->toBe($image->path)
        ->and(Image::count())
        ->toBe(0);
});

it('expects an avatar', function () {
    Storage::fake('images');
    Storage::fake('avatars');

    // setup the world
    $user = User::factory()->create();

    // hit the upload route
    login($user)
        ->put(route('user.avatar.update'))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['avatar']);
});

it('expects a valid image id', function () {
    Storage::fake('images');
    Storage::fake('avatars');

    // setup the world
    $user = User::factory()->create();

    // hit the upload route
    login($user)
        ->put(route('user.avatar.update'), [
            'avatar' => 5,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['avatar']);
});

it('expects the image to belong to the authenticated user', function () {
    Storage::fake('images');
    Storage::fake('avatars');

    // setup the world
    $user = User::factory()->create();

    // upload the image first
    $imageId = uploadImage();

    // hit the upload route
    login()
        ->put(route('user.avatar.update'), [
            'avatar' => $imageId,
        ])
        ->assertStatus(403);
});

it('cannot update guest avatar', function () {
    Storage::fake('images');
    Storage::fake('avatars');

    // hit the upload route
    guest()
        ->put(route('user.avatar.update'))
        ->assertStatus(401);
});
