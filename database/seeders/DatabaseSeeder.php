<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\{Catagory, Company, Contact, Offer, Order, Product, Tag, User};

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Product::factory(1000)->create();
        User::factory()->create([
            "name" => "yamen rami abuwarda",
            "email" => "yamenrami@gmail.com",
            "password" => 12345678 ,  
        ]);
        // Catagory::factory(1000)->create();
        // Tag::factory(1000)->create();
        // Company::factory(1000)->create();
        // Offer::factory(1000)->create();
    }
}
