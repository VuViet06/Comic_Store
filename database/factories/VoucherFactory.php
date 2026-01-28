<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('VOUCHER-????-####'),
            'type' => fake()->randomElement(['percent', 'fixed']),
            'value' => fake()->numberBetween(5, 50),
            'max_discount' => fake()->numberBetween(50, 500),
            'min_order_amount' => fake()->numberBetween(50, 500),
            'usage_limit' => fake()->numberBetween(1, 100),
            'used_count' => 0,
            'starts_at' => fake()->dateTime(),
            'ends_at' => fake()->dateTimeBetween('now', '+1 month'),
            'is_active' => true,
        ];
    }
}
