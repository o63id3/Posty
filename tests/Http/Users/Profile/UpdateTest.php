<?php

declare(strict_types=1);

use App\Models\User;

it('can update own information', function () {
    // setup the world
    $user = User::factory()->create();

    // setup request body
    $requestBody = [
        'first_name' => 'My',
        'last_name' => 'Name',
        'email' => 'my@mail.com',
        'username' => 'username',
    ];

    // hit the update route
    login($user)
        ->put(route('user.profile.update'), $requestBody)
        ->assertOk();

    expect($user->refresh())
        ->first_name->toBe($requestBody['first_name'])
        ->last_name->toBe($requestBody['last_name'])
        ->email->toBe($requestBody['email'])
        ->username->toBe($requestBody['username']);
});

it('expects first, last name, email, and username', function () {
    // setup the world
    $user = User::factory()->create();

    // hit the update route
    login($user)
        ->put(route('user.profile.update'))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'username']);
});

it('expects unique email', function () {
    // setup the world
    $user = User::factory()->create();

    // setup request body
    $requestBody = [
        'first_name' => 'My',
        'last_name' => 'Name',
        'email' => User::factory()->create()->email,
        'username' => 'username',
    ];

    // hit the update route
    login($user)
        ->put(route('user.profile.update'), $requestBody)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('expects unique email except own email', function () {
    // setup the world
    $user = User::factory()->create();

    // setup request body
    $requestBody = [
        'first_name' => 'My',
        'last_name' => 'Name',
        'email' => $user->email,
        'username' => 'username',
    ];

    // hit the update route
    login($user)
        ->put(route('user.profile.update'), $requestBody)
        ->assertOk();
});

it('expects unique username', function () {
    // setup the world
    $user = User::factory()->create();

    // setup request body
    $requestBody = [
        'first_name' => 'My',
        'last_name' => 'Name',
        'email' => 'email@mail.com',
        'username' => User::factory()->create()->username,
    ];

    // hit the update route
    login($user)
        ->put(route('user.profile.update'), $requestBody)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['username']);
});

it('expects unique username except own username', function () {
    // setup the world
    $user = User::factory()->create();

    // setup request body
    $requestBody = [
        'first_name' => 'My',
        'last_name' => 'Name',
        'email' => 'email@mail.com',
        'username' => $user->username,
    ];

    // hit the update route
    login($user)
        ->put(route('user.profile.update'), $requestBody)
        ->assertOk();
});

it('requires a login', function () {
    // hit the update route
    guest()
        ->put(route('user.profile.update'))
        ->assertStatus(401);
});
