<?php

use App\Models\{Cart, Offer, Product, User};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

function cardGridProducts(int $count = 2): Collection
{
    return new Collection(array_map(fn ($number) => Product::create([
        'name' => "Grid product $number", 'desc' => 'Grid test product',
        'price' => 20, 'int_price' => 10, 'original_price' => 20,
        'quantity' => 20, 'image' => 'assets/images/test.png', 'featured' => false,
    ]), range(1, $count)));
}

function cardGridComponentNames(string $html): array
{
    preg_match_all('/wire:snapshot="([^"]+)"/', $html, $matches);

    return array_map(fn ($snapshot) => json_decode(
        html_entity_decode($snapshot, ENT_QUOTES), true, flags: JSON_THROW_ON_ERROR
    )['memo']['name'], $matches[1]);
}

test('a full products page mounts one cards component without child cart components', function () {
    $products = cardGridProducts(32);
    $response = $this->get(route('products'))->assertOk();
    $names = cardGridComponentNames($response->getContent());

    expect(array_count_values($names)['cards'] ?? 0)->toBe(1);
    expect($names)->not->toContain('add-to-cart');
    expect(count($names))->toBeLessThanOrEqual(8);
    foreach ($products as $product) {
        $response->assertSee($product->name);
    }
});

test('products pagination and searching still select the correct grid products', function () {
    $products = cardGridProducts(33);
    $this->get(route('products', ['page' => 2]))->assertOk()
        ->assertSee($products->last()->name)->assertDontSee('Grid product 1<', false);
    $this->get(route('products', ['search' => 'Grid product 33']))->assertOk()
        ->assertSee('Grid product 33')->assertDontSee('Grid product 1<', false);
});

test('collection cards preserve cart and wishlist actions for each product', function () {
    $user = User::factory()->create();
    $products = cardGridProducts();
    $offer = Offer::create([
        'name' => 'Card sale', 'type' => 'products', 'discount_type' => 'fixed_amount',
        'discount_value' => 5, 'is_active' => true,
        'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
    ]);
    $offer->products()->attach($products->last());
    $this->actingAs($user);
    $component = Livewire::test('cards', [
        'products' => $products, 'products_offer' => Offer::with(['products', 'categories'])->get(),
    ]);

    $component->assertSee('Grid product 1')->assertSee('Grid product 2')->assertSee('$15')
        ->call('addCart', $products->last()->id)->assertDispatched('cart-updated')
        ->call('addCart', $products->last()->id)->assertSee('$15')
        ->call('addCart', $products->first()->id)
        ->call('addFavoriate', $products->first()->id)
        ->call('addFavoriate', $products->first()->id);

    $cart = Cart::where('user_id', $user->id)->firstOrFail();
    $this->assertDatabaseHas('cart_product', [
        'cart_id' => $cart->id, 'product_id' => $products->last()->id, 'quantity' => 2,
    ]);
    $this->assertDatabaseHas('cart_product', [
        'cart_id' => $cart->id, 'product_id' => $products->first()->id, 'quantity' => 1,
    ]);
    $this->assertDatabaseHas('favoriates', ['user_id' => $user->id, 'product_id' => $products->first()->id]);
    $this->assertDatabaseCount('favoriates', 1);
    $this->assertDatabaseCount('carts', 1);
});

test('collection card actions send guests to login', function (string $action) {
    $products = cardGridProducts();
    Livewire::test('cards', ['products' => $products, 'products_offer' => new Collection])
        ->call($action, $products->last()->id)->assertRedirect(route('login'));
    $this->assertDatabaseCount('carts', 0);
    $this->assertDatabaseCount('favoriates', 0);
})->with(['addCart', 'addFavoriate']);

test('collection cards accept an empty product collection', function () {
    Livewire::test('cards', ['products' => new Collection, 'products_offer' => new Collection])
        ->assertDontSee('ec-product-content');
});

test('offers render one cards component for each product offer group', function () {
    $products = cardGridProducts();
    foreach (['First offer', 'Second offer'] as $name) {
        $offer = Offer::create([
            'name' => $name, 'type' => 'products', 'discount_type' => 'fixed_amount',
            'discount_value' => 5, 'is_active' => true,
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
        ]);
        $offer->products()->attach($products->modelKeys());
    }
    $response = $this->get(route('home.offers'))->assertOk()
        ->assertSee('First offer')->assertSee('Second offer');
    $names = cardGridComponentNames($response->getContent());
    expect(array_count_values($names)['cards'] ?? 0)->toBe(2);
    expect($names)->not->toContain('add-to-cart');
});

test('offers pagination shares offer queries and keeps discounts from other pages', function () {
    $user = User::factory()->create();
    $products = cardGridProducts();
    $cart = Cart::create(['user_id' => $user->id]);
    $cart->products()->attach($products->first(), ['quantity' => 1]);
    foreach (range(1, 11) as $number) {
        $offer = Offer::create([
            'name' => "Public sale $number", 'type' => 'products', 'discount_type' => 'fixed_amount',
            'discount_value' => $number === 11 ? 10 : 1, 'is_active' => true,
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
        ]);
        $offer->products()->attach($products->modelKeys());
    }
    foreach (['Coupon sale', 'Expired sale', 'Inactive sale'] as $name) {
        Offer::create([
            'name' => $name, 'type' => 'global', 'discount_type' => 'fixed_amount',
            'discount_value' => 1, 'is_active' => $name !== 'Inactive sale',
            'code' => $name === 'Coupon sale' ? 'PRIVATE' : null,
            'start_date' => now()->subDays(2),
            'end_date' => $name === 'Expired sale' ? now()->subDay() : now()->addDay(),
        ]);
    }
    $this->actingAs($user);
    DB::enableQueryLog();
    DB::flushQueryLog();
    $this->get(route('home.offers'))->assertOk()
        ->assertSee('Public sale 1<', false)->assertDontSee('Public sale 11<', false)
        ->assertDontSee('Coupon sale<', false)->assertDontSee('Expired sale<', false)
        ->assertDontSee('Inactive sale<', false)->assertSee('$10')
        ->assertSee("wire:click=\"gotoPage(2, 'page')\"", false);

    $queries = DB::getQueryLog();
    foreach (['from "offers"', 'inner join "products_offer"', 'inner join "categories_offer"'] as $table) {
        expect(array_filter($queries, fn ($query) => str_contains($query['query'], $table)))->toHaveCount(1);
    }
    expect(count($queries))->toBeLessThanOrEqual(13);

    $this->get(route('home.offers', ['page' => 2]))->assertOk()
        ->assertSee('Public sale 11<', false)->assertDontSee('Public sale 1<', false);
});

test('offers render safely when no active public offers exist', function () {
    $this->get(route('home.offers'))->assertOk()->assertSee('No Available Offers');
});
