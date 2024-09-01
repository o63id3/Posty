<?php

declare(strict_types=1);

use App\Http\Resources\PostImageResource;
use App\Models\PostImage;

test('make', function () {
    $image = PostImage::factory()->create();

    $resource = PostImageResource::make($image)->resolve();

    expect($resource)
        ->toHaveKey('id', $image->id)
        ->toHaveKey('url', $image->url);
});
