<?php

declare(strict_types=1);

use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\PostLikeController;
use App\Http\Controllers\User\UserFollowerController;
use App\Http\Controllers\User\UserFollowingsController;
use App\Http\Controllers\User\UserPostController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::prefix('users/{user}')
    ->name('user.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/posts', [UserPostController::class, 'index'])->name('posts.index');

        Route::get('/followers', [UserFollowerController::class, 'index'])->name('followers.index');

        Route::get('/followings', [UserFollowingsController::class, 'index'])->name('following.index');
    });

Route::name('user.')
    ->middleware('auth')
    ->group(function () {
        Route::delete('/followings/{target}', [UserFollowingsController::class, 'destroy'])->name('following.destroy');

        Route::post('/followers/{target}', [UserFollowerController::class, 'store'])->name('followers.store');
        Route::delete('/followers/{target}', [UserFollowerController::class, 'destroy'])->name('followers.destroy');
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
