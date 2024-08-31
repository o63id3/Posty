<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

final class UserFollowerController
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user): JsonResponse
    {
        $followers = $user
            ->followers()
            ->cursorPaginate();

        return response()->json([
            'data' => UserResource::collection($followers),
            'meta' => [
                'hasMorePages' => $followers->hasMorePages(),
            ],
            'links' => [
                'nextPageUrl' => $followers->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $target): JsonResponse
    {
        $user = type($request->user())->as(User::class);

        Gate::authorize('follow', $target);

        $user
            ->followers()
            ->attach($target);

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $target): JsonResponse
    {
        $user = type($request->user())->as(User::class);

        $user
            ->followers()
            ->detach($target);

        return response()->json();
    }
}
