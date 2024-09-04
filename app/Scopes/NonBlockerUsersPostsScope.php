<?php

declare(strict_types=1);

namespace App\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

final class NonBlockerUsersPostsScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->check()) {
            $builder->whereNotIn('posts.user_id', function ($query) {
                $user = type(auth()->user())->as(User::class);

                $query->select('blocker_id')
                    ->from('blocks')
                    ->whereColumn('blocker_id', 'posts.user_id')
                    ->where('user_id', $user->id);
            });
        }
    }
}
