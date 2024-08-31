<?php

declare(strict_types=1);

use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\PostLikeController;
use App\Http\Controllers\User\UserPostController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::group(['middleware' => ['auth']], function () {
    Route::get(
        '/users/{user}/posts',
        [UserPostController::class, 'index']
    )->name('user.posts.index');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get(
        '/posts',
        [PostController::class, 'index']
    )->name('posts.index');

    Route::post(
        '/posts/{parent?}',
        [PostController::class, 'store']
    )->name('posts.store');

    Route::get(
        '/posts/{post}',
        [PostController::class, 'show']
    )->name('posts.show');

    Route::put(
        '/posts/{post}',
        [PostController::class, 'update']
    )->name('posts.update');

    Route::delete(
        '/posts/{post}',
        [PostController::class, 'destroy']
    )->name('posts.destroy');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get(
        '/posts/{post}/likes',
        [PostLikeController::class, 'index']
    )->name('post.likes.index');

    Route::post(
        '/posts/{post}/likes',
        [PostLikeController::class, 'store']
    )->name('post.likes.store');

    Route::delete(
        '/posts/{post}/likes',
        [PostLikeController::class, 'destroy']
    )->name('post.likes.destroy');
});
