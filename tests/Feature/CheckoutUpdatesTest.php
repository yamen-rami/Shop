<?php

use App\Models\{Cart, Catagory, Offer, Product, User};
use Illuminate\Support\Facades\DB;

function checkoutUpdateFixture(): array
{
    $user = User::factory()->create();
    $category = Catagory::create(['name' => 'Checkout category', 'desc' => 'Checkout test']);
    $cart = Cart::create(['user_id' => $user->id]);
    $products = [];
    foreach (range(1, 8) as $number) {
        $product = Product::create([
            'name' => "Checkout product $number", 'desc' => 'Checkout test',
            'price' => 10, 'int_price' => 8, 'original_price' => 10,
            'quantity' => 20, 'image' => 'assets/images/test.png', 'catagory_id' => $category->id,
        ]);
        $products[] = $product;
        $cart->products()->attach($product, ['quantity' => 2]);
    }
    foreach (range(1, 3) as $discount) {
        $offer = Offer::create([
            'name' => "Checkout sale $discount", 'type' => 'products', 'discount_type' => 'fixed_amount',
            'discount_value' => $discount, 'is_active' => true,
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
        ]);
        $offer->products()->attach(array_map(fn ($product) => $product->id, $products));
    }

    return [$user, $cart, $products];
}

function checkoutSnapshots(string $html): array
{
    preg_match_all('/wire:snapshot="([^"]+)"/', $html, $matches);

    return array_map(fn ($snapshot) => json_decode(
        html_entity_decode($snapshot, ENT_QUOTES), true, flags: JSON_THROW_ON_ERROR
    ), $matches[1]);
}

test('checkout quantity mutations do not fetch the cart twice', function (string $action, int $quantity, int $initialQuantity) {
    [$user, $cart, $products] = checkoutUpdateFixture();
    $this->actingAs($user);
    $cart->products()->updateExistingPivot($products[0]->id, ['quantity' => $initialQuantity]);
    $snapshots = checkoutSnapshots($this->get(route('checkout'))->assertOk()->getContent());
    $snapshot = collect($snapshots)->first(fn ($snapshot) => $snapshot['memo']['name'] === 'increment_decrement');
    app()->forgetScopedInstances();
    DB::enableQueryLog();
    DB::flushQueryLog();

    $response = $this->postJson(app('livewire')->getUpdateUri(), ['components' => [[
        'snapshot' => json_encode($snapshot), 'updates' => [],
        'calls' => [['method' => $action, 'params' => [], 'path' => '']],
    ]]], ['X-Livewire' => 'true'])->assertOk();

    $queries = DB::getQueryLog();
    expect(array_filter($queries, fn ($query) => str_contains($query['query'], 'from "carts"')))->toHaveCount(1);
    expect(count($queries))->toBeLessThanOrEqual(4);
    $updated = json_decode($response->json('components.0.snapshot'), true);
    expect($updated['data']['quantity'])->toBe($quantity);
    if ($quantity === 0) {
        $this->assertDatabaseMissing('cart_product', ['cart_id' => $cart->id, 'product_id' => $products[0]->id]);
    } else {
        $this->assertDatabaseHas('cart_product', [
            'cart_id' => $cart->id, 'product_id' => $products[0]->id, 'quantity' => $quantity,
        ]);
    }
})->with([['increment', 3, 2], ['decrement', 1, 2], ['decrement', 0, 1]]);

test('checkout batches share cart and offers across every cart updated listener', function (?string $code) {
    [$user, $cart, $products] = checkoutUpdateFixture();
    if ($code !== null) {
        Offer::create([
            'name' => 'Checkout coupon', 'type' => 'coupon', 'code' => 'SAVE', 'discount_type' => 'fixed_amount',
            'discount_value' => 5, 'is_active' => true,
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
        ]);
    }
    $this->actingAs($user);
    $snapshots = checkoutSnapshots($this->get(route('checkout'))->assertOk()->getContent());
    $cart->products()->updateExistingPivot($products[0]->id, ['quantity' => 3]);
    app()->forgetScopedInstances();
    DB::enableQueryLog();
    DB::flushQueryLog();
    $listeners = array_values(array_filter($snapshots, fn ($snapshot) => in_array(
        $snapshot['memo']['name'], ['increment_decrement', 'items', 'receipt', 'home', 'count']
    )));

    $response = $this->postJson(app('livewire')->getUpdateUri(), ['components' => array_map(fn ($snapshot) => [
        'snapshot' => json_encode($snapshot),
        'updates' => in_array($snapshot['memo']['name'], ['items', 'receipt']) ? ['code' => $code] : [],
        'calls' => [['method' => '__dispatch', 'params' => ['cart-updated', []], 'path' => '']],
    ], $listeners)], ['X-Livewire' => 'true'])->assertOk();

    $queries = DB::getQueryLog();
    foreach (['from "carts"', 'from "offers"', 'inner join "cart_product"', 'inner join "products_offer"', 'inner join "categories_offer"'] as $table) {
        $expected = $table === 'from "offers"' && $code !== null ? 2 : 1;
        expect(array_filter($queries, fn ($query) => str_contains($query['query'], $table)))->toHaveCount($expected);
    }
    expect(count($queries))->toBeLessThanOrEqual(6);
    foreach ($response->json('components') as $component) {
        $snapshot = json_decode($component['snapshot'], true);
        if ($snapshot['memo']['name'] === 'receipt') {
            expect($component['effects']['html'])->toContain($code === 'SAVE' ? '$85' : '$119', '$170');
        }
        if ($snapshot['memo']['name'] === 'count') {
            expect($component['effects']['html'])->toContain('17');
        }
        if ($snapshot['memo']['name'] === 'increment_decrement') {
            expect($snapshot['data']['quantity'])->toBe(
                $snapshot['data']['productId'] === $products[0]->id ? 3 : 2
            );
        }
    }
})->with([null, 'SAVE', 'INVALID']);
