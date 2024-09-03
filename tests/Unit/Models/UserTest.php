<?php

declare(strict_types=1);

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

test('posts', function () {
    $user = User::factory()->create();
    Post::factory(10)->recycle($user)->create();
    Post::factory(10)->create();

    expect($user->posts->pluck('user_id'))
        ->each->toBe($user->id);
});

test('likes', function () {
    $users = User::factory(5)->create();
    Like::factory(50)->recycle($users)->create();

    expect($users->first()->likes->pluck('user_id'))
        ->each->toBe($users->first()->id);
});

test('own posts likes', function () {
    $users = User::factory(5)->create();
    Like::factory(50)->recycle($users)->create();

    $user = $users->random();

    expect($user->ownPostsLikes->pluck('post_id'))
        ->each(function ($value) use ($user) {
            return expect(Post::where('id', $value->value)->first())
                ->user_id->toBe($user->id);
        });
});

test('following', function () {
    $user = User::factory()->create();
    $target = User::factory()->create();

    $user->following()->attach($target->id);

    expect($user->following()->count())
        ->toBe(1)
        ->and($user->following()->first()->id)
        ->toBe($target->id);
});

test('follower', function () {
    $user = User::factory()->create();
    $target = User::factory()->create();

    $user->following()->attach($target->id);

    expect($target->followers()->count())
        ->toBe(1)
        ->and($target->followers()->first()->id)
        ->toBe($user->id);
});

test('images', function () {
    $users = User::factory(2)->hasImages(5)->create();

    $user = $users->random();

    expect($user->images->pluck('user_id'))
        ->each->toBe($user->id);
});

test('default avatar url', function () {
    $user = User::factory()->create();

    expect($user->avatar)->toBeNull()
        ->and($user->avatar_url)->toBe(asset('img/default-avatar.png'));
});

test('custom avatar url', function () {
    $user = User::factory()->create([
        'avatar' => 'avatars/123.png',
    ]);

    expect($user->avatar)->toBe('avatars/123.png')
        ->and($user->avatar_url)->toBe(Storage::disk('public')->url('avatars/123.png'));
});

test('blocking', function () {
    $user = User::factory()->create();
    $target = User::factory()->create();

    $user->blocking()->attach($target->id);

    expect($user->blocking()->count())
        ->toBe(1)
        ->and($user->blocking()->first()->id)
        ->toBe($target->id);
});

test('blockers', function () {
    $user = User::factory()->create();
    $target = User::factory()->create();

    $user->blockers()->attach($target->id);

    expect($user->blockers()->count())
        ->toBe(1)
        ->and($user->blockers()->first()->id)
        ->toBe($target->id);
});
