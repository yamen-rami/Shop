<?php

use App\Livewire\{Catalog, RecordTable};
use App\Models\{Catagory, Company, Product, Tag, User};
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

test('search endpoints return at most twenty records with working search and pagination', function (string $resource, string $model) {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $rows = [];
    foreach (range(1, 45) as $number) {
        $row = ['name' => sprintf('Option %02d', $number)];
        if ($model !== Tag::class) $row['desc'] = 'Description';
        if ($model === Product::class) $row += ['price' => 12.35, 'int_price' => 8, 'original_price' => 12.35, 'quantity' => 10];
        $rows[] = $row;
    }
    $model::insert($rows);
    DB::enableQueryLog();
    DB::flushQueryLog();
    $first = $this->getJson(route('select-options', $resource))->assertOk()
        ->assertJsonCount(20, 'results')->assertJsonPath('pagination.more', true);
    $this->getJson(route('select-options', ['resource' => $resource, 'page' => 2]))->assertOk()
        ->assertJsonCount(20, 'results')->assertJsonPath('results.0.text', 'Option 21');
    $this->getJson(route('select-options', ['resource' => $resource, 'page' => 3]))->assertOk()
        ->assertJsonCount(5, 'results')->assertJsonPath('pagination.more', false);
    $this->getJson(route('select-options', ['resource' => $resource, 'q' => ' Option 45 ']))->assertOk()
        ->assertJsonCount(1, 'results')->assertJsonPath('results.0.text', 'Option 45');
    foreach (DB::getQueryLog() as $query) {
        if (!str_contains($query['query'], 'count(')) expect($query['query'])->toContain('limit 20');
    }
    expect(array_keys($first->json('results.0')))->toBe($resource === 'products' ? ['id', 'text', 'price'] : ['id', 'text']);
})->with([
    ['categories', Catagory::class], ['companies', Company::class],
    ['products', Product::class], ['tags', Tag::class],
]);

test('selected option lookup is capped at twenty and returns names outside the first page', function () {
    foreach (range(1, 25) as $number) Catagory::create(['name' => "Category $number", 'desc' => 'Category description']);
    $this->getJson(route('select-options', ['resource' => 'categories', 'ids' => [25]]))->assertOk()
        ->assertJsonCount(1, 'results')->assertJsonPath('results.0.id', 25);
    $this->getJson(route('select-options', ['resource' => 'categories', 'ids' => range(1, 21)]))->assertUnprocessable();
});

test('public category and company lookups work while product and tag lookups require access', function () {
    $this->getJson(route('select-options', 'categories'))->assertOk();
    $this->getJson(route('select-options', 'companies'))->assertOk();
    $this->getJson(route('select-options', 'products'))->assertUnauthorized();
    $this->getJson(route('select-options', 'tags'))->assertForbidden();
    $this->actingAs(User::factory()->create(['role' => 'user']));
    $this->getJson(route('select-options', 'products'))->assertOk();
    $this->getJson(route('select-options', 'tags'))->assertForbidden();
});

test('company filters combine with category and search for both public and admin products', function () {
    $category = Catagory::create(['name' => 'Coffee', 'desc' => 'Drinks']);
    $company = Company::create(['name' => 'Coffee company']);
    $wanted = Product::create(['name' => 'Coffee beans', 'desc' => 'Fresh coffee', 'price' => 10, 'int_price' => 8, 'original_price' => 10,
        'quantity' => 1, 'image' => 'test.png', 'catagory_id' => $category->id]);
    $company->products()->attach($wanted);
    Product::create(['name' => 'Other coffee', 'desc' => 'Fresh coffee', 'price' => 10, 'int_price' => 8, 'original_price' => 10,
        'quantity' => 1, 'image' => 'test.png', 'catagory_id' => $category->id]);
    Livewire::withQueryParams(['company' => $company->id, 'category' => $category->id])->test(Catalog::class)
        ->assertSet('companyId', (string) $company->id)
        ->set('search', 'Coffee')->assertViewHas('products', fn ($page) => $page->pluck('id')->toArray() === [$wanted->id])
        ->call('clearFilters')->assertSet('companyId', '')->assertSet('categoryId', '');
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    Livewire::test(RecordTable::class, ['resource' => 'products'])->set('companyId', (string) $company->id)
        ->set('categoryId', (string) $category->id)->set('search', 'Coffee')
        ->assertViewHas('records', fn ($page) => $page->pluck('id')->toArray() === [$wanted->id])
        ->call('clearFilters')->assertSet('companyId', '');
});

test('product and offer create forms do not load option models before searching', function (string $route) {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    DB::enableQueryLog();
    DB::flushQueryLog();
    $this->get(route($route))->assertOk()->assertSee('data-select2-url=', false);
    $queries = array_filter(DB::getQueryLog(), fn ($query) => preg_match('/from "(products|catagories|tags)"/', $query['query']));
    expect($queries)->toBeEmpty();
})->with(['product.create', 'offer.create', 'company.create', 'order.create']);
