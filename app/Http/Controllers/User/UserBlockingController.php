<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UserBlockingController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = type($request->user())->as(User::class);

        $blocking = $user
            ->blocking()
            ->cursorPaginate();

        return response()->json([
            'data' => UserResource::collection($blocking),
            'meta' => [
                'hasMorePages' => $blocking->hasMorePages(),
            ],
            'links' => [
                'nextPageUrl' => $blocking->nextPageUrl(),
            ],
        ]);

        return response()->json();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user): JsonResponse
    {
        $blocker = type($request->user())->as(User::class);

        $blocker
            ->blocking()
            ->attach($user);

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        $blocker = type($request->user())->as(User::class);

        $blocker
            ->blocking()
            ->detach($user);

        return response()->json();
    }
}
