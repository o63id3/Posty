<?php

declare(strict_types=1);

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('can upload image', function () {
    Storage::fake('images');

    // setup the world
    $user = User::factory()->create();

    // hit the upload route
    $response = login($user)
        ->post(route('images.store'), [
            'image' => UploadedFile::fake()->image('test.jpg'),
        ])
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
            ],
        ]);

    $imageId = $response['data']['id'];

    expect(Image::find($imageId))
        ->user->toBe($user);
});

it('expects an image field', function () {
    Storage::fake('images');

    // hit the upload route
    login()
        ->post(route('images.store'))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['image']);
});

it('expects the image field to be an image', function ($mimeType) {
    Storage::fake('images');

    // hit the upload route
    login()
        ->post(route('images.store'), [
            'image' => UploadedFile::fake()->create(name: 'test.txt', mimeType: $mimeType),
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['image']);
})->with([
    'txt',
    'exe',
]);

it('expects the image to be less than 2 MB', function () {
    ini_set('memory_limit', '-1');

    Storage::fake('images');

    // hit the upload route
    login()
        ->post(route('images.store'), [
            'image' => UploadedFile::fake()->image('test.jpg', 14000, 10000), // this generates an image over than 2 MB
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['image']);
});

it('cannot upload images for guests', function () {
    // hit the upload route
    guest()
        ->post(route('images.store'), [
            'image' => UploadedFile::fake()->image('test.jpg'),
        ])
        ->assertStatus(401);
});
