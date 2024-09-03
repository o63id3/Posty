<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Like;
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
        $users = User::factory(10)
            ->hasFollowers(3)
            ->hasFollowing(1)
            ->create();
        $posts = Post::factory(100)
            ->hasPosts(3)
            ->recycle($users)
            ->create();

        Like::factory(130)->recycle($users)->recycle($posts)->create();
    }
}
