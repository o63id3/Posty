<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\PostImage;

test('post', function () {
    $post = Post::factory()->create();
    $image = PostImage::factory()->recycle($post)->create();

    expect($image->post)
        ->toBe($post);
});

test('url', function () {
    $image = PostImage::factory()->create([
        'path' => 'avatars/123.png',
    ]);

    expect($image->path)->toBe('avatars/123.png')
        ->and($image->url)->toBe(Storage::disk('public')->url('avatars/123.png'));
});
