<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100, 1000);
        $discount = fake()->randomFloat(2, 0, $subtotal * 0.2);
        $total = $subtotal - $discount;

        return [
            'user_id' => User::factory(),
            'code' => 'ORD-' . fake()->unique()->numerify('##########'),
            'subtotal_amount' => $subtotal,
            'discount_amount' => $discount,
            'total_amount' => $total,
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'shipping_address_line' => fake()->streetAddress(),
            'shipping_ward' => fake()->word(),
            'shipping_province' => fake()->state(),
            'shipping_postal_code' => fake()->postcode(),
            'payment_method' => fake()->randomElement(['cod', 'bank_transfer', 'momo', 'vnpay']),
            'payment_status' => fake()->randomElement(['unpaid', 'pending', 'paid', 'failed', 'refunded']),
            'order_status' => fake()->randomElement(['pending', 'shipping', 'completed', 'cancelled', 'returned']),
            'customer_note' => fake()->sentence(),
        ];
    }
}
