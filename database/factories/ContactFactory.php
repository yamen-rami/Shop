<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{Contact, User};

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
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
            "title"=> fake()->realText(10),
            "email"=>fake()->email() ,
            "desc" =>fake()->realText(10) ,
            "user_id" => User::factory(1)->create(),
        ];
    }
}
