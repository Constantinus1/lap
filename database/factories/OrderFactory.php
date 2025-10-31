<?php

namespace Database\Factories;

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
        return [
            'order_amount' => fake()->randomFloat(2, 50, 3000),
            'order_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'payment_method' => fake()->randomElement(['invoice', 'credit_card']),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->email(),
            'shipping_address' => fake()->streetAddress(),
            'shipping_city' => fake()->city(),
            'shipping_state' => fake()->state(),
            'shipping_country' => fake()->country(),
            'shipping_pin_code' => fake()->postcode(),
        ];
    }
}
