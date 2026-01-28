<?php

namespace Database\Factories;

use App\Models\Comic;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryTransaction>
 */
class InventoryTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'comic_id' => Comic::factory(),
            'type' => fake()->randomElement(['import', 'sale', 'return', 'adjustment']),
            'quantity_change' => fake()->numberBetween(1, 50),
            'order_id' => fake()->boolean(70) ? Order::factory() : null,
            'user_id' => fake()->boolean(50) ? User::factory() : null,
            'note' => fake()->sentence(),
        ];
    }
}
