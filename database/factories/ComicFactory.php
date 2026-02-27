<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comic>
 */
class ComicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3) . ' ' . Str::random(8);

        return [
            'category_id' => Category::factory(),
            'publisher_id' => Publisher::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'published_year' => fake()->year(),
            'edition_type' => fake()->randomElement([
                'regular',
                'special',
                'limited',
                'collector',   // some old records still use this spelling
                'collectors',  // newer UI value
            ]),
            'condition' => fake()->randomElement([
                // values that have been seen throughout the application
                'new',
                'like_new',
                'good',
                'fair',
                'used',
                'discontinued',
                'in_stock',
                'coming_soon',
                'out_of_stock',
            ]),
            'series' => fake()->word(),
            'volume' => fake()->numberBetween(1, 20),
            'price' => fake()->randomFloat(2, 10, 100),
            'cover' => 'https://via.placeholder.com/300x400',
            'stock' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
