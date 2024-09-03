<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Model::shouldBeStrict(! app()->isProduction());

        JsonResource::withoutWrapping();

        Route::bind('post', function ($id) {
            return Post::query()
                ->where('id', $id)
                ->whereNotIn('user_id', function ($query) {
                    $user = type(auth()->user())->as(User::class);

                    $query->select('blocker_id')
                        ->from('blocks')
                        ->whereColumn('blocker_id', 'posts.user_id')
                        ->where('user_id', $user->id);
                })
                ->firstOrFail();
        });

        Route::bind('user', function ($username) {
            return User::query()
                ->where('username', $username)
                ->whereNotIn('id', function ($query) {
                    $user = type(auth()->user())->as(User::class);

                    $query->select('blocker_id')
                        ->from('blocks')
                        ->whereColumn('blocker_id', 'users.id')
                        ->where('user_id', $user->id);
                })
                ->firstOrFail();
        });
    }
}
