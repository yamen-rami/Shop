<?php

use App\Models\{Catagory, Offer, Product, User};
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

function categoryAdmin(): User
{
    $user = User::factory()->create();
    $user->forceFill(['role' => 'admin'])->save();

    return $user;
}

test('category index mounts live filters in the admin layout', function () {
    $this->actingAs(categoryAdmin())->get(route('catagory.index'))->assertOk()
        ->assertSee('<title>Categories</title>', false)
        ->assertSee('wire:model.live.debounce.300ms="search"', false)
        ->assertSee('wire:model.live="sort"', false)
        ->assertSee('data-update-uri=', false);
});

test('category search matches names and descriptions and combines with sorting', function () {
    $this->actingAs(categoryAdmin());
    Catagory::create(['name' => 'Coffee', 'desc' => 'Fresh drinks']);
    Catagory::create(['name' => 'Tea', 'desc' => 'Coffee alternatives']);
    Catagory::create(['name' => 'Books', 'desc' => 'Reading']);

    Livewire::test('catagory-table')->set('search', ' Coffee ')
        ->set('sortBy', 'name')->set('sort', 'asc')
        ->assertViewHas('catagores', fn ($categories) => $categories->pluck('name')->all() === ['Coffee', 'Tea'])
        ->set('sort', 'desc')
        ->assertViewHas('catagores', fn ($categories) => $categories->pluck('name')->all() === ['Tea', 'Coffee'])
        ->set('search', 'does not exist')->assertSee('No categories found.');
});

test('category filters reset pagination and can be cleared', function () {
    $this->actingAs(categoryAdmin());
    foreach (range(1, 31) as $number) {
        Catagory::create(['name' => "Category $number", 'desc' => 'Pagination test']);
    }
    Livewire::test('catagory-table')->call('gotoPage', 2)
        ->assertViewHas('catagores', fn ($categories) => $categories->currentPage() === 2 && $categories->count() === 1)
        ->set('search', 'Category 31')->assertSet('paginators.page', 1)
        ->assertViewHas('catagores', fn ($categories) => $categories->total() === 1)
        ->set('sortBy', 'name')->set('sort', 'asc')->call('clearFilters')
        ->assertSet('search', '')->assertSet('sortBy', 'id')->assertSet('sort', 'desc')
        ->assertViewHas('catagores', fn ($categories) => $categories->total() === 31 && $categories->count() === 30);
});

test('category filters restore query string values and reject unsafe sort values', function () {
    $this->actingAs(categoryAdmin());
    Catagory::create(['name' => 'Coffee', 'desc' => 'Drinks']);
    Livewire::withQueryParams(['search' => 'Coffee', 'sort_by' => 'name', 'sort' => 'asc'])
        ->test('catagory-table')->assertSet('search', 'Coffee')->assertSet('sortBy', 'name')->assertSet('sort', 'asc')
        ->set('sortBy', 'unknown column')->set('sort', 'invalid direction')->assertSee('Coffee');
});

test('category filters cannot be accessed by guests or non admins', function (bool $authenticated) {
    if ($authenticated) {
        $this->actingAs(User::factory()->create());
    }
    Livewire::test('catagory-table')->assertForbidden();
})->with([false, true]);

test('category show lists only its products with ten rows and eager loaded tags', function () {
    $category = Catagory::create(['name' => 'Coffee', 'desc' => 'Drinks']);
    $other = Catagory::create(['name' => 'Books', 'desc' => 'Reading']);
    $products = [];
    foreach (range(1, 11) as $number) {
        $products[] = Product::create([
            'name' => "Category product $number", 'desc' => 'Product description',
            'catagory_id' => $category->id, 'price' => 10, 'int_price' => 8, 'original_price' => 10,
            'quantity' => 5, 'image' => 'assets/images/test.png',
        ]);
    }
    Product::create([
        'name' => 'Other category product', 'desc' => 'Different category',
        'catagory_id' => $other->id, 'price' => 10, 'int_price' => 8, 'original_price' => 10,
        'quantity' => 5, 'image' => 'assets/images/test.png',
    ]);
    $offer = Offer::create([
        'name' => 'Category sale', 'type' => 'categories', 'discount_type' => 'fixed_amount',
        'discount_value' => 2, 'is_active' => true,
        'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
    ]);
    $offer->categories()->attach($category);
    $this->actingAs(categoryAdmin());
    DB::enableQueryLog();
    DB::flushQueryLog();
    $this->get(route('catagory.show', $category))->assertOk()
        ->assertViewHas('products', fn ($page) => $page->perPage() === 10 && $page->count() === 10 && $page->total() === 11)
        ->assertSee('Category sale')->assertDontSee('Other category product');
    $queries = DB::getQueryLog();
    expect(array_filter($queries, fn ($query) => str_contains($query['query'], 'from "catagories"')))->toHaveCount(1);
    expect(array_filter($queries, fn ($query) => str_contains($query['query'], 'inner join "product_tags"')))->toHaveCount(1);

    $this->get(route('catagory.show', ['catagory' => $category, 'page' => 2]))->assertOk()
        ->assertViewHas('products', fn ($page) => $page->count() === 1 && $page->first()->id === $products[0]->id);
});

test('category show has an empty state when no products use it', function () {
    $category = Catagory::create(['name' => 'Empty category', 'desc' => 'No products']);
    $this->actingAs(categoryAdmin())->get(route('catagory.show', $category))->assertOk()
        ->assertSee('No products use this category.');
});
