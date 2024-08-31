<?php

declare(strict_types=1);

use App\Models\Image;
use App\Models\User;

test('user', function () {
    $user = User::factory()->create();
    $image = Image::factory()->recycle($user)->create();

    expect($image->user)
        ->toBe($user);
});
