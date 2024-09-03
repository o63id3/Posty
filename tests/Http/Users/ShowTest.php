<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

it('can show basic user info', function () {
    $user = User::factory()->create();

    login()
        ->get(route('users.show', $user))
        ->assertOk()
        ->assertExactJson([
            'data' => [
                'id' => $user->id,
                'fullName' => $user->full_name,
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'username' => $user->username,
                'avatar' => $user->avatar_url,
                'isFollowing' => false,
                'createdAt' => $user->created_at,
                'updatedAt' => $user->updated_at,

                'postsCount' => $user->posts()->count(),
                'likesCount' => $user->ownPostsLikes()->count(),
                'followingCount' => $user->following()->count(),
                'followersCount' => $user->followers()->count(),
            ],
        ]);
});

it('can show basic user info with email for own account', function () {
    $user = User::factory()->create();

    login($user)
        ->get(route('users.show', $user))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->where('data', fn ($data) => $data['email'] === $user->email));
});

it('cannot show basic user info for guest', function () {
    $user = User::factory()->create();

    guest()
        ->get(route('users.show', $user))
        ->assertStatus(401)
        ->assertJsonMissing(['data']);
});

it('cannot load the user if the owner is blocker', function () {
    // setup the world
    $user = User::factory()->create();
    $blocker = User::factory()->create();
    $blocker->blocking()->attach($user);

    // hit the show route
    login($user)
        ->get(route('users.show', $blocker))
        ->assertNotFound()
        ->assertJsonMissing(['data']);
});
