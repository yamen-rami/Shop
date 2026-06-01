<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Product;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
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
            "desc" => fake()->realText(10) ,
            "price" => fake()->numberBetween(10 , 100) , 
            "int_price" => fake()->numberBetween(8 , 80 ), 
            "quantity" => fake()->numberBetween(10 , 100),
            "image" => fake()->imageUrl,
        ];
    }
}
