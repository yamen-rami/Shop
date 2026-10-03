<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{Company, Product};

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // create a factory
            "name" => fake()->name , 
            'desc' => fake()->realText(10),
            'image' => asset("assets/images/about/about-10.png"),
        ];
    }
}
