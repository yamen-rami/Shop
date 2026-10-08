<?php

use App\Models\Color;
use App\Models\Product;
use Database\Seeders\StoreCatalogSeeder;

test('catalog seed attaches portable images and colors through the product morph relation', function () {
    $this->seed(StoreCatalogSeeder::class);

    $this->assertDatabaseCount('products', 22);
    $this->assertDatabaseCount('images', 22);

    foreach (Product::with('images.colors', 'images.imageable')->get() as $product) {
        expect($product->images)->toHaveCount(1);
        $image = $product->images->first();
        expect($image->imageable->is($product))->toBeTrue();
        expect($image->path)->toStartWith('assets/images/');
        expect(is_file(public_path($image->path)))->toBeTrue();
        expect($image->colors)->not->toBeNull();
    }

    $jacket = Product::where('name', 'Red Quilted Puffer Jacket')->firstOrFail();
    expect($jacket->images->first()->colors->name)->toBe('Red');
});

test('repeating the catalog seed preserves stock and does not duplicate images or colors', function () {
    $this->seed(StoreCatalogSeeder::class);
    $product = Product::firstOrFail();
    $product->update(['quantity' => 3]);
    $imageIds = Product::with('images')->get()->flatMap->images->pluck('id')->all();
    $colorCount = Color::count();

    $this->seed(StoreCatalogSeeder::class);

    $this->assertDatabaseCount('products', 22);
    $this->assertDatabaseCount('images', 22);
    $this->assertDatabaseCount('colors', $colorCount);
    expect($product->fresh()->quantity)->toBe(3);
    expect(Product::with('images')->get()->flatMap->images->pluck('id')->all())->toBe($imageIds);
});
