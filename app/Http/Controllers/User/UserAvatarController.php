<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

final class UserAvatarController
{
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => ['required', 'exists:images,id'],
        ]);

        $user = type($request->user())->as(User::class);

        $image = Image::find($request->avatar);

        Gate::authorize('setAsAvatar', $image);

        $user
            ->update([
                'avatar' => $image->path,
            ]);

        $image->delete();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = type($request->user())->as(User::class);

        Gate::authorize('deleteAvatar', $user);

        $user
            ->update([
                'avatar' => null,
            ]);

        return response()->json();
    }
}
