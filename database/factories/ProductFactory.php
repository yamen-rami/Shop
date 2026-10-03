<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{Catagory, Product, Tag};

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
            "catagory_id" => Catagory::factory()->create() ,
            "featured" => false ,
            "price" => fake()->numberBetween(10 , 100) , 
            "original_price" => fake()->numberBetween(10 , 100) , 
            "int_price" => fake()->numberBetween(8 , 80 ), 
            "quantity" => fake()->numberBetween(10 , 100),
            "image" => asset("assets/images/service/man.png"),
        ];
    }
}
