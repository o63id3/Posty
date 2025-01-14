<?php

declare(strict_types=1);

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

test('user', function () {
    $user = User::factory()->create();
    $post = Post::factory()->recycle($user)->create();

    expect($post->user)
        ->toBe($user);
});

test('is owner', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $post = Post::factory()->recycle($userA)->create();

    expect($post->isOwner($userA))
        ->toBeTrue()
        ->and($post->isOwner($userB))
        ->toBeFalse();
});

test('parent', function () {
    $parent = Post::factory()->create();
    $post = Post::factory()->hasParent($parent)->create();

    expect($post->parent)
        ->toBe($parent);
});

test('posts', function () {
    $parent = Post::factory()->create();
    Post::factory(10)->hasParent($parent)->create();
    Post::factory(10)->create();

    expect($parent->posts->pluck('parent_id'))
        ->each->toBe($parent->id);
});

test('likes', function () {
    $posts = Post::factory(5)->create();
    Like::factory(30)->recycle($posts)->create();

    expect($posts->first()->likes->pluck('post_id'))
        ->each->toBe($posts->first()->id);
});

test('is liked', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    expect($post->isLiked($user))
        ->toBeFalse();

    $post = Post::factory()
        ->hasLikes(1, ['user_id' => $user])
        ->create();

    expect($post->isLiked($user))
        ->toBeTrue();
});

test('images', function () {
    $posts = Post::factory(2)->hasImages(2)->create();

    $post = $posts->random();
    expect($post->images->pluck('post_id'))
        ->each->toBe($post->id);
});
