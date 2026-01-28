<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ShippingPartner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'shipping_partner_id' => ShippingPartner::factory(),
            'service_name' => fake()->randomElement(['Standard', 'Express', 'Overnight']),
            'tracking_code' => 'TRK-' . fake()->unique()->numerify('##########'),
            'status' => fake()->randomElement(['pending', 'booked', 'picking', 'shipping', 'delivered', 'failed', 'returned']),
            'raw_response' => json_encode(['status' => 'ok']),
            'booked_at' => fake()->dateTime(),
            'delivered_at' => fake()->boolean(70) ? fake()->dateTime() : null,
        ];
    }
}
