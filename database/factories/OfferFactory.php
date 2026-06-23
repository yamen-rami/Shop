<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Offer;

/**
 * @extends Factory<Offer>
 */
class OfferFactory extends Factory
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
            "name" => fake()->name,
            "code" => fake()->unique()->name,
            "discount_value" => fake()->numberBetween(10, 100),
            "is_active" => false,
            "discount_type" => "percentage",
            "start_date" => fake()->date(),
            "end_date" => fake()->date(),

        ];
    }
}
