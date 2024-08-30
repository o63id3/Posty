<?php

use App\Models\User;
use App\Models\Post;
use App\Models\Like;

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
