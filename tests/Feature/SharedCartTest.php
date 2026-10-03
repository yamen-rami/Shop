<?php

use App\Models\{Cart, Catagory, Offer, Product, User};
use App\Services\StorefrontData;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

function sharedCartProduct(): Product
{
    return Product::create([
        'name' => 'Shared cart product', 'desc' => 'Test product',
        'price' => 10, 'int_price' => 8, 'original_price' => 10,
        'quantity' => 20, 'image' => 'assets/images/test.png', 'featured' => true,
    ]);
}

test('cart reads share one model and one products query', function () {
    $user = User::factory()->create();
    $cart = Cart::create(['user_id' => $user->id]);
    $product = sharedCartProduct();
    $cart->products()->attach($product, ['quantity' => 3]);
    $this->actingAs($user);

    DB::enableQueryLog();
    DB::flushQueryLog();
    $data = app(StorefrontData::class);
    $sharedCart = $data->cart();

    expect($data->cart())->toBe($sharedCart);
    expect($data->cartCount())->toBe(3);
    expect(DB::getQueryLog())->toHaveCount(2);

    $cart->products()->updateExistingPivot($product->id, ['quantity' => 4]);
    $data->forgetCart();
    expect($data->cartCount())->toBe(4);
});

test('a missing cart is cached and has a zero count', function () {
    $this->actingAs(User::factory()->create());
    DB::enableQueryLog();
    DB::flushQueryLog();
    $data = app(StorefrontData::class);

    expect($data->cart())->toBeNull();
    expect($data->cart())->toBeNull();
    expect($data->cartCount())->toBe(0);
    expect(DB::getQueryLog())->toHaveCount(1);
});

test('guests do not query for a cart', function () {
    DB::enableQueryLog();
    DB::flushQueryLog();
    expect(app(StorefrontData::class)->cart())->toBeNull();
    expect(app(StorefrontData::class)->cartCount())->toBe(0);
    expect(DB::getQueryLog())->toBeEmpty();
});

test('cart components render safely without a cart', function (bool $authenticated) {
    if ($authenticated) {
        $this->actingAs(User::factory()->create());
    }
    $offers = new \Illuminate\Database\Eloquent\Collection;
    Livewire::test('items', ['globalCart' => null, 'offers' => $offers])
        ->assertSee('Cart Empty');
    Livewire::test('receipt', ['globalCart' => null, 'globalOffer' => $offers])
        ->assertSet('totalPrice', 0)
        ->assertSet('originalPrice', 0)
        ->assertSet('discountTotal', 0);
    Livewire::test('count')->assertSet('getCount', 0);
    Livewire::test('home')->assertSet('totalPrice', 0);
    Livewire::test('increment_decrement', ['product' => sharedCartProduct()->id, 'globalCart' => null])
        ->assertSet('quantity', 0)->call('increment')->call('decrement')
        ->assertSet('quantity', 0);
})->with([false, true]);

test('storefront pages use one cart lookup and at most thirteen queries', function (string $page) {
    $user = User::factory()->create();
    $product = sharedCartProduct();
    $cart = Cart::create(['user_id' => $user->id]);
    $cart->products()->attach($product, ['quantity' => 2]);
    $category = Catagory::create(['name' => 'Test category', 'desc' => 'Test category']);
    $product->update(['catagory_id' => $category->id]);
    for ($i = 0; $i < 7; $i++) {
        $extraProduct = sharedCartProduct();
        $extraProduct->update(['catagory_id' => $category->id]);
        $cart->products()->attach($extraProduct, ['quantity' => 1]);
    }
    $offer = Offer::create([
        'name' => 'Sale', 'type' => 'products', 'discount_type' => 'fixed_amount',
        'discount_value' => 1, 'is_active' => true,
        'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
    ]);
    $offer->products()->attach($product);
    $offer->categories()->attach($category);
    $this->actingAs($user);
    DB::enableQueryLog();
    DB::flushQueryLog();

    $url = $page === 'showProduct' ? route($page, $product) : route($page);
    $this->get($url)->assertOk();
    $queries = DB::getQueryLog();
    $cartQueries = array_filter($queries, fn ($query) => str_contains($query['query'], 'from "carts"'));

    expect($cartQueries)->toHaveCount(1);
    expect(count($queries))->toBeLessThanOrEqual(13);
})->with(['home', 'products', 'showProduct', 'checkout']);

test('checkout redirects safely when the cart is missing or empty', function (bool $emptyCart) {
    $user = User::factory()->create();
    if ($emptyCart) {
        Cart::create(['user_id' => $user->id]);
    }
    $this->actingAs($user)->get(route('checkout'))->assertRedirect(route('home'));
})->with([false, true]);

test('quantity controls refresh shared cart data after mutations', function () {
    $user = User::factory()->create();
    $product = sharedCartProduct();
    $cart = Cart::create(['user_id' => $user->id]);
    $cart->products()->attach($product, ['quantity' => 1]);
    $this->actingAs($user);

    Livewire::test('increment_decrement', ['product' => $product->id, 'globalCart' => $cart])
        ->assertSet('quantity', 1)
        ->call('increment')->assertSet('quantity', 2)
        ->call('decrement')->assertSet('quantity', 1)
        ->call('decrement')->assertSet('quantity', 0);
    expect(app(StorefrontData::class)->cartCount())->toBe(0);
});

test('expired carts and other users carts are excluded', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    Cart::create(['user_id' => $otherUser->id]);
    $expired = Cart::create(['user_id' => $user->id]);
    $expired->forceFill(['created_at' => now()->subDays(2)])->save();
    $this->actingAs($user);

    expect(app(StorefrontData::class)->cart())->toBeNull();
    expect(app(StorefrontData::class)->cartCount())->toBe(0);
});

test('favorites counts including zero are shared and can be refreshed', function (bool $hasFavorite) {
    $user = User::factory()->create();
    $product = sharedCartProduct();
    $otherUser = User::factory()->create();
    $otherUser->favoriates()->create(['product_id' => $product->id]);
    if ($hasFavorite) {
        $user->favoriates()->create(['product_id' => $product->id]);
    }
    $this->actingAs($user);
    DB::enableQueryLog();
    DB::flushQueryLog();
    $data = app(StorefrontData::class);

    expect($data->favoriates())->toBe($hasFavorite ? 1 : 0);
    expect($data->favoriates())->toBe($hasFavorite ? 1 : 0);
    expect(DB::getQueryLog())->toHaveCount(1);

    $user->favoriates()->create(['product_id' => sharedCartProduct()->id]);
    $data->forgetFavoriates();
    expect($data->favoriates())->toBe($hasFavorite ? 2 : 1);
})->with([false, true]);

test('guests receive a zero favorites count without database queries', function () {
    DB::enableQueryLog();
    DB::flushQueryLog();
    expect(app(StorefrontData::class)->favoriates())->toBe(0);
    expect(app(StorefrontData::class)->favoriates())->toBe(0);
    expect(DB::getQueryLog())->toBeEmpty();
});

test('navbar and menu use one favorites count query on the products page', function () {
    $user = User::factory()->create();
    $user->favoriates()->create(['product_id' => sharedCartProduct()->id]);
    $this->actingAs($user);
    DB::enableQueryLog();
    DB::flushQueryLog();

    $this->get(route('products'))->assertOk();
    $queries = array_filter(DB::getQueryLog(), fn ($query) => str_contains($query['query'], 'from "favoriates"'));
    expect($queries)->toHaveCount(1);
});
