<?php

declare(strict_types=1);

use App\Http\Controllers\ImageController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\PostLikeController;
use App\Http\Controllers\User\UserAvatarController;
use App\Http\Controllers\User\UserFollowerController;
use App\Http\Controllers\User\UserFollowingsController;
use App\Http\Controllers\User\UserPostController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware('auth')->post('images', [ImageController::class, 'store'])->name('images.store');

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

        Route::put('/avatar', [UserAvatarController::class, 'update'])->name('avatar.update');
        Route::delete('/avatar', [UserAvatarController::class, 'destroy'])->name('avatar.destroy');
    });

Route::group(['middleware' => ['auth']], function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

    Route::get('/followers', [UserFollowerController::class, 'index'])->name('followers.index');

    Route::get('/followings', [UserFollowingsController::class, 'index'])->name('following.index');
});

Route::prefix('posts')
    ->name('posts.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::post('/{parent?}', [PostController::class, 'store'])->name('store');
        Route::get('/{post}', [PostController::class, 'show'])->name('show');
        Route::put('/{post}', [PostController::class, 'update'])->name('update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
    });

Route::prefix('posts/{post}/likes')
    ->name('post.likes.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [PostLikeController::class, 'index'])->name('index');
        Route::post('/', [PostLikeController::class, 'store'])->name('store');
        Route::delete('/', [PostLikeController::class, 'destroy'])->name('destroy');
    });
