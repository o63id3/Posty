<?php

use App\Models\User;
use App\Models\Post;

test('posts', function () {
    $user = User::factory()->create();
    Post::factory(10)->recycle($user)->create();
    Post::factory(10)->create();

    expect($user->posts->pluck('user_id'))
        ->each->toBe($user->id);
});
