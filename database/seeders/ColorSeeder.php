<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Black', 'White', 'Grey', 'Red', 'Blue', 'Navy', 'Green', 'Sage', 'Mint', 'Yellow', 'Pink', 'Cream', 'Brown', 'Orange', 'Purple', 'Multicolor'] as $name) {
            Color::firstOrCreate(['name' => $name]);
        }
    }
}
