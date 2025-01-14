<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
final class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body' => $this->faker->paragraph(),
            'parent_id' => null,
            'user_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the model has no parent.
     */
    public function hasParent(?Post $post = null): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $post ?? Post::factory(),
        ]);
    }
}
