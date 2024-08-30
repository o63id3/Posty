<?php

use App\Http\Controllers\Post\PostController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

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
