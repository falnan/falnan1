<?php

namespace Database\Factories;

use App\Enums\PostVisibility;
use App\Enums\UserRole;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Post::class;

    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'author_id' => User::role([UserRole::Author, UserRole::SuperAdmin])
                ->inRandomOrder()
                ->first()
                ->id,
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(8, true),
            'status' => 'published',
            'visibility' => fake()
                ->randomElement(PostVisibility::cases())
                ->value,
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
