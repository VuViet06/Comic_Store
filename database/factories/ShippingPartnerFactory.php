<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShippingPartner>
 */
class ShippingPartnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company() . ' Shipping';

        return [
            'name' => $name,
            'code' => Str::slug($name),
            'api_base_url' => fake()->url(),
            'is_active' => true,
        ];
    }
}
