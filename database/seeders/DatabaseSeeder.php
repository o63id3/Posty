<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();
        $posts = Post::factory(20)->recycle($users)->create();

        // User::factory()->create([
        // 'name' => 'Test User',
        // 'email' => 'test@example.com',
        // ]);
    }
}
