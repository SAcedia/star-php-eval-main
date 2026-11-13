<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Real-world news categories, drawn uniquely so seeded categories look plausible.
     */
    private const NAMES = [
        'World', 'Politics', 'Business', 'Technology', 'Science', 'Health',
        'Sports', 'Entertainment', 'Culture', 'Travel', 'Lifestyle', 'Opinion',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(self::NAMES),
        ];
    }
}
