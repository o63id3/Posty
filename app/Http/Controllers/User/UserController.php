<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

final class UserController
{
    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        $user->loadCount(['posts', 'ownPostsLikes', 'followers', 'following']);

        return response()->json([
            'data' => UserResource::make($user),
        ]);
    }
}
