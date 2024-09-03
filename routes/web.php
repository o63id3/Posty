<?php

declare(strict_types=1);

use App\Http\Controllers\ImageController;
use App\Http\Controllers\Post\PostCommentController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\PostImageController;
use App\Http\Controllers\Post\PostLikeController;
use App\Http\Controllers\User\UserAvatarController;
use App\Http\Controllers\User\UserBlockingController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserFollowerController;
use App\Http\Controllers\User\UserFollowingsController;
use App\Http\Controllers\User\UserPostController;
use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware('auth')->post('images', [ImageController::class, 'store'])->name('images.store');

Route::middleware('auth')->get('users/{user:username}', [UserController::class, 'show'])->name('users.show');

Route::prefix('users/{user:username}')
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

        Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    });

Route::group(['middleware' => ['auth']], function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

    Route::get('/followers', [UserFollowerController::class, 'index'])->name('followers.index');

    Route::get('/followings', [UserFollowingsController::class, 'index'])->name('following.index');

    Route::get('/blocking', [UserBlockingController::class, 'index'])->name('blocking.index');
    Route::post('/{user:username}/block', [UserBlockingController::class, 'store'])->name('blocking.store');
    Route::delete('/{user:username}/block', [UserBlockingController::class, 'destroy'])->name('blocking.destroy');
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

Route::prefix('posts/{post}/posts')
    ->name('post.posts.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [PostCommentController::class, 'index'])->name('index');
    });

Route::prefix('posts/{post}/images')
    ->name('post.images.')
    ->middleware('auth')
    ->group(function () {
        Route::post('/', [PostImageController::class, 'store'])->name('store');
        Route::delete('/{image}', [PostImageController::class, 'destroy'])->name('destroy');
    });
