<?php

declare(strict_types=1);

use App\Actions\NewPost;
use App\Models\Post;
use App\Models\User;

it('can create post', function () {
    $action = $this->app->make(NewPost::class);

    $user = User::factory()->create();
    $attributes = [
        'body' => 'This is post body!',
    ];

    $post = $action->handle($user, $attributes);

    expect($post->fresh())
        ->user->toBe($user)
        ->parent->toBeNull()
        ->body->toBe($attributes['body']);
});

it('can create post with parent', function () {
    $action = $this->app->make(NewPost::class);

    $user = User::factory()->create();
    $attributes = [
        'body' => 'This is post body!',
    ];
    $parent = Post::factory()->create();

    $post = $action->handle($user, $attributes, $parent);

    expect($post->fresh())
        ->parent->toBe($parent);
});

it('can create post with image', function () {
    $action = $this->app->make(NewPost::class);

    $imageId = uploadImage();

    $user = User::factory()->create();
    $attributes = [
        'body' => 'This is post body!',
        'images' => [
            $imageId,
        ]
    ];
    $parent = Post::factory()->create();

    $post = $action->handle($user, $attributes, $parent);

    expect($post->fresh())
        ->parent->toBe($parent)
        ->images->toHaveLength(1);
});
