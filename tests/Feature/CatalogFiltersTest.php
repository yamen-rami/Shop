<?php

use App\Livewire\Catalog;
use App\Models\Catagory;
use App\Models\Color;
use App\Models\Company;
use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;

function filterProduct(string $name, float $price, array $attributes = []): Product
{
    return Product::create([
        'name' => $name, 'desc' => 'Catalog filter fixture', 'price' => $price,
        'original_price' => $price, 'int_price' => 5, 'quantity' => 10,
        ...$attributes,
    ]);
}

test('price bounds use the whole available catalog and price filtering includes both endpoints', function () {
    filterProduct('Below range', 9.99);
    $low = filterProduct('Lower endpoint', 20.25);
    $high = filterProduct('Upper endpoint', 40.75);
    filterProduct('Above range', 50.01);
    filterProduct('Unavailable expensive product', 1000, ['quantity' => 0]);

    Livewire::test(Catalog::class)
        ->assertViewHas('priceFloor', 9.0)->assertViewHas('priceCeiling', 51.0)
        ->set('minPrice', '20.25')->set('maxPrice', '40.75')
        ->assertViewHas('products', fn ($products) => $products->modelKeys() === [$low->id, $high->id])
        ->assertViewHas('priceFloor', 9.0)->assertViewHas('priceCeiling', 51.0);

    $this->get(route('products', ['min-price' => '20.25', 'max-price' => '40.75']))
        ->assertOk()->assertSee('Lower endpoint')->assertSee('Upper endpoint')
        ->assertDontSee('Below range')->assertDontSee('Above range');
});

test('color filtering matches secondary photos and excludes images belonging to other models', function () {
    $red = Color::create(['name' => 'Red']);
    $blue = Color::create(['name' => 'Blue']);
    $mixed = filterProduct('Blue main red secondary', 30);
    $mixed->images()->create(['path' => 'blue.jpg', 'color_id' => $blue->id]);
    $mixed->images()->create(['path' => 'red.jpg', 'color_id' => $red->id]);
    $blueOnly = filterProduct('Only blue photos', 30);
    $blueOnly->images()->create(['path' => 'blue.jpg', 'color_id' => $blue->id]);
    $noPhoto = filterProduct('No product photos', 30);
    Image::create(['path' => 'unrelated.jpg', 'color_id' => $red->id,
        'imageable_type' => Company::class, 'imageable_id' => $noPhoto->id]);
    $soldOut = filterProduct('Sold out red', 30, ['quantity' => 0]);
    $soldOut->images()->create(['path' => 'red.jpg', 'color_id' => $red->id]);

    Livewire::test(Catalog::class)->call('selectColor', (string) $red->id)
        ->assertSet('colorId', (string) $red->id)
        ->assertViewHas('products', fn ($products) => $products->modelKeys() === [$mixed->id])
        ->call('selectColor', (string) $red->id)->assertSet('colorId', '')
        ->assertViewHas('products', fn ($products) => $products->total() === 3);

    $this->get(route('products', ['color' => $red->id]))->assertOk()
        ->assertSee('Blue main red secondary')->assertDontSee('Only blue photos')
        ->assertDontSee('No product photos')->assertDontSee('Sold out red');
});

test('price and color combine with existing filters and reset returns to the first page', function () {
    $category = Catagory::factory()->create();
    $company = Company::factory()->create();
    $red = Color::create(['name' => 'Red']);
    $match = filterProduct('Matching item', 25, ['catagory_id' => $category->id]);
    $match->companies()->attach($company);
    $match->images()->create(['path' => 'red.jpg', 'color_id' => $red->id]);
    filterProduct('Wrong item', 25);

    Livewire::test(Catalog::class)->call('gotoPage', 2)
        ->set('minPrice', '20')->assertSet('paginators.page', 1)
        ->set('maxPrice', '30')->set('search', 'Matching')
        ->set('categoryId', (string) $category->id)->set('companyId', (string) $company->id)
        ->call('selectColor', (string) $red->id)->set('sort', 'price-desc')
        ->assertViewHas('products', fn ($products) => $products->modelKeys() === [$match->id])
        ->call('clearFilters')->assertSet('minPrice', '')->assertSet('maxPrice', '')
        ->assertSet('colorId', '')->assertSet('search', '')->assertSet('categoryId', '')
        ->assertSet('companyId', '')->assertSet('sort', 'oldest')->assertSet('paginators.page', 1)
        ->assertViewHas('products', fn ($products) => $products->total() === 2);
});

test('empty catalogs keep usable range bounds and invalid price text is ignored', function () {
    Livewire::test(Catalog::class)->assertViewHas('priceFloor', 0.0)->assertViewHas('priceCeiling', 1.0)
        ->assertSee('No products found.');
    $product = filterProduct('Single price product', 30);
    Livewire::test(Catalog::class)->set('minPrice', 'invalid')->set('maxPrice', 'invalid')
        ->assertViewHas('products', fn ($products) => $products->modelKeys() === [$product->id])
        ->set('minPrice', '40')->set('maxPrice', '20')
        ->assertViewHas('products', fn ($products) => $products->modelKeys() === [$product->id]);
});

test('wishlist filters remain scoped to the signed in users products', function () {
    $user = User::factory()->create();
    $red = Color::create(['name' => 'Red']);
    $saved = filterProduct('Saved red product', 25, ['quantity' => 0]);
    $other = filterProduct('Unsaved red product', 25);
    foreach ([$saved, $other] as $product) {
        $product->images()->create(['path' => 'red.jpg', 'color_id' => $red->id]);
    }
    $user->favoriates()->create(['product_id' => $saved->id]);

    Livewire::actingAs($user)->test(Catalog::class, ['wishlist' => true])
        ->set('minPrice', '20')->set('maxPrice', '30')->call('selectColor', (string) $red->id)
        ->assertViewHas('products', fn ($products) => $products->modelKeys() === [$saved->id]);
});
