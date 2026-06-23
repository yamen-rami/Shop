<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Catagory;

/**
 * @extends Factory<Catagory>
 */
class CatagoryFactory extends Factory
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
            "name" => fake()->name , 
            "desc" => fake()->realText(10),
        ];
    }
}
