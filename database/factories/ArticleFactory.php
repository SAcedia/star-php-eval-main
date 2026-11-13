<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state: a published article.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(6, true);

        return [
            'title' => rtrim($title, '.'),
            'summary' => fake()->sentences(2, true),
            'body' => fake()->paragraphs(4, true),
            'permalink' => Str::slug($title),
            'published_at' => fake()->dateTimeBetween('-1 year'),
            'status' => 'published',
            'author' => fake()->name(),
            'category_id' => Category::factory(),
        ];
    }

    /**
     * An unpublished article: no publication date yet, but we keep track
     * of when it was drafted.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
            'drafted_at' => fake()->dateTimeBetween('-2 weeks'),
        ]);
    }

    /**
     * An article retired from publication over a year ago.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
            'published_at' => fake()->dateTimeBetween('-2 years', '-1 year'),
        ]);
    }
}
