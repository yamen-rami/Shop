<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Order;

/**
 * @extends Factory<Order>
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
            //
            "name" => fake()->name(),
            "price" => fake()->numberBetween(10 , 1000),
            "quantity" => fake()->numberBetween(10 , 1000),
            "location" => fake()->realText(10),
        ];
    }
}
