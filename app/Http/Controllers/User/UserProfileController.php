<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class UserProfileController
{
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): JsonResponse
    {
        $user = type($request->user())->as(User::class);

        $validated = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', Rule::unique('users', 'email')->ignore($user->id)],
            'username' => ['required', Rule::unique('users', 'username')->ignore($user->id)],
        ]);

        $user->update($validated);

        return response()->json();
    }
}
