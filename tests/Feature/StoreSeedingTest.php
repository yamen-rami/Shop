<?php

use App\Models\{Catagory, Company, Offer, Product, Tag, User};
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\{DB, Hash};

beforeEach(function () {
    config(['seeding.admin_email' => 'owner@example.com', 'seeding.admin_password' => 'Portfolio-Test-Password-2026']);
});

test('store seed builds a complete portable catalog with offers and one administrator', function () {
    $this->seed(DatabaseSeeder::class);

    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseCount('products', 22);
    $this->assertDatabaseCount('images', 22);
    $this->assertDatabaseCount('catagories', 6);
    $this->assertDatabaseCount('companies', 4);
    $this->assertDatabaseCount('tags', 8);
    $this->assertDatabaseCount('offers', 4);
    $this->assertDatabaseCount('orders', 0);
    $this->assertDatabaseCount('contacts', 0);
    $this->assertDatabaseCount('carts', 0);
    $admin = User::firstOrFail();
    expect($admin->role)->toBe('admin');
    expect($admin->email_verified_at)->not->toBeNull();
    expect(Hash::check('Portfolio-Test-Password-2026', $admin->password))->toBeTrue();

    foreach (Product::with('catagory', 'companies', 'tags', 'images')->get() as $product) {
        expect($product->catagory)->not->toBeNull();
        expect($product->companies)->toHaveCount(1);
        expect($product->tags)->toHaveCount(2);
        expect((float) $product->price)->toBeGreaterThan((float) $product->int_price);
        expect($product->quantity)->toBeGreaterThan(0);
        expect($product->images)->toHaveCount(1);
        expect(is_file(public_path($product->images->first()->path)))->toBeTrue();
        expect($product->images->first()->path)->toStartWith('assets/');
    }
    foreach (Company::all() as $company) {
        expect(is_file(public_path($company->image)))->toBeTrue();
    }
    expect(Product::where('featured', true)->count())->toBeGreaterThanOrEqual(8);
    expect(Offer::active()->count())->toBe(4);
    expect(Offer::where('type', 'categories')->first()->categories)->toHaveCount(1);
    expect(Offer::where('type', 'products')->first()->products)->toHaveCount(2);
    expect(Offer::where('code', 'WELCOME10')->first()->type)->toBe('coupon');

    $this->get(route('home'))->assertOk()->assertSee('Red Quilted Puffer Jacket');
    $this->get(route('products'))->assertOk()->assertSee('White Statement-Collar Blouse');
    $this->get(route('home.offers'))->assertOk()->assertSee('Knitwear Season Special');
    $this->actingAs($admin)->get(route('dashboard'))->assertOk();
});

test('running the store seed again preserves edited stock and passwords without duplicate relations', function () {
    $this->seed(DatabaseSeeder::class);
    $product = Product::firstOrFail();
    $product->update(['quantity' => 3, 'price' => '99.50']);
    $admin = User::firstOrFail();
    $admin->update(['password' => 'Changed-Password-2026']);
    $pivotCounts = collect(['company_product', 'product_tags', 'products_offer', 'categories_offer'])
        ->mapWithKeys(fn ($table) => [$table => DB::table($table)->count()]);

    $this->seed(DatabaseSeeder::class);

    $this->assertDatabaseCount('products', 22);
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseCount('offers', 4);
    expect($product->fresh()->quantity)->toBe(3);
    expect((float) $product->fresh()->price)->toBe(99.5);
    expect(Hash::check('Changed-Password-2026', $admin->fresh()->password))->toBeTrue();
    foreach ($pivotCounts as $table => $count) {
        $this->assertDatabaseCount($table, $count);
    }
});

test('seed refuses to promote a customer using the bootstrap email', function () {
    User::factory()->create(['email' => 'owner@example.com', 'role' => 'user']);
    expect(fn () => $this->seed(DatabaseSeeder::class))->toThrow(RuntimeException::class);
    $this->assertDatabaseCount('products', 0);
    expect(User::first()->role)->toBe('user');
});

test('seed rejects weak configured passwords before creating records', function () {
    config(['seeding.admin_password' => 'admin']);
    expect(fn () => $this->seed(DatabaseSeeder::class))->toThrow(RuntimeException::class);
    $this->assertDatabaseCount('users', 0);
    $this->assertDatabaseCount('products', 0);
});
