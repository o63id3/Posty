<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UserFollowingsController
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user): JsonResponse
    {
        $following = $user
            ->following()
            ->cursorPaginate();

        return response()->json([
            'data' => UserResource::collection($following),
            'meta' => [
                'hasMorePages' => $following->hasMorePages(),
            ],
            'links' => [
                'nextPageUrl' => $following->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $target): JsonResponse
    {
        $user = type($request->user())->as(User::class);

        $user
            ->following()
            ->detach($target);

        return response()->json();
    }
}
