<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $blog = $user->blog ?? Blog::factory()->create(['user_id' => $user->id]);

        return [
            'user_id' => $user->id,
            'blog_id' => $blog->id,
            'title'   => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
        ];
    }
}
