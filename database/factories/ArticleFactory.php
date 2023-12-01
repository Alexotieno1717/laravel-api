<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'image' => 'https://source.unsplash.com/random', // Replace with actual path or use faker for images
            'user_id' => User::factory(),
        ];
    }

    /**
     * Configure the factory to have tags after creating an article.
     *
     * @return $this
     */
    public function withTags(): static
    {
        return $this->afterCreating(function (Article $article) {
            $article->tags()->attach(Tag::factory()->count(3)->create());
        });
    }
}
