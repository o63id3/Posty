<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ImageController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:2048'],
        ]);

        $file = $request->file('image');

        $filePath = $file->Store("/images/{$request->user()->username}", 'public');

        $image = $request
            ->user()
            ->images()
            ->create([
                'size' => $file->getSize(),
                'path' => $filePath,
            ]);

        return response()->json([
            'data' => [
                'id' => $image->id,
            ],
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
