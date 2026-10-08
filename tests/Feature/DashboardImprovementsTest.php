<?php

use App\Livewire\{Catalog, PublicOffers, RecordTable};
use App\Models\{Cart, Catagory, Company, Contact, Offer, Order, Product, Tag, User};
use Livewire\Livewire;

function dashboardUser(bool $admin = true): User
{
    return User::factory()->create(['role' => $admin ? 'admin' : 'user']);
}

function dashboardProduct(array $attributes = []): Product
{
    $product = Product::create(array_merge([
        'name' => 'Coffee product', 'desc' => 'Fresh coffee beans', 'price' => 12.35,
        'int_price' => 8, 'original_price' => 12.35, 'quantity' => 10,
        'featured' => true,
    ], $attributes));
    $color = \App\Models\Color::firstOrCreate(['name' => 'Brown']);
    $product->images()->create(['path' => 'assets/images/test.png', 'color_id' => $color->id]);

    return $product;
}

test('admin listings use Vuexy and live filters', function (string $route) {
    $this->actingAs(dashboardUser())->get(route($route))->assertOk()
        ->assertSee('layout-content-navbar', false)
        ->assertSee('wire:model.live.debounce.300ms="search"', false)
        ->assertDontSee('ec-header', false);
})->with(['product.index', 'company.index', 'tag.index', 'offer.index', 'productsOffer', 'offerCoupons', 'catagoryOffers', 'order.index', 'contact.index']);

test('product filters combine search category featured stock and safe sorting', function () {
    $this->actingAs(dashboardUser());
    $category = Catagory::create(['name' => 'Drinks', 'desc' => 'Drinks category']);
    $match = dashboardProduct(['catagory_id' => $category->id]);
    dashboardProduct(['name' => 'Other coffee', 'catagory_id' => $category->id, 'featured' => false]);
    dashboardProduct(['quantity' => 0, 'catagory_id' => $category->id]);
    dashboardProduct();
    Livewire::test(RecordTable::class, ['resource' => 'products'])
        ->set('search', ' fresh ')->set('categoryId', (string) $category->id)
        ->set('featured', '1')->set('status', 'in-stock')->set('sortBy', 'untrusted')->set('sort', 'invalid')
        ->assertViewHas('records', fn ($page) => $page->pluck('id')->all() === [$match->id])
        ->call('clearFilters')->assertViewHas('records', fn ($page) => $page->total() === 4);
});

test('coupon search never includes another offer type and inactive includes expired offers', function () {
    $this->actingAs(dashboardUser());
    foreach (['coupon', 'products', 'categories'] as $type) {
        Offer::create(['name' => 'Matching sale', 'code' => $type === 'coupon' ? 'MATCH' : null,
            'type' => $type, 'discount_type' => 'fixed_amount', 'discount_value' => 2,
            'is_active' => true, 'start_date' => now()->subDays(2), 'end_date' => now()->subDay()]);
    }
    Livewire::test(RecordTable::class, ['resource' => 'coupon-offers'])->set('search', 'Matching')
        ->set('status', 'inactive')->assertViewHas('records', fn ($page) => $page->total() === 1 && $page->first()->type === 'coupon')
        ->set('status', 'active')->assertViewHas('records', fn ($page) => $page->isEmpty());
    expect(Offer::catagory()->count())->toBe(1);
});

test('listing filter changes reset pagination', function () {
    $this->actingAs(dashboardUser());
    foreach (range(1, 31) as $id) { Tag::create(['name' => "Tag $id"]); }
    Livewire::test(RecordTable::class, ['resource' => 'tags'])->call('gotoPage', 2)
        ->assertViewHas('records', fn ($page) => $page->currentPage() === 2)
        ->set('search', 'Tag 31')->assertSet('paginators.page', 1)
        ->assertViewHas('records', fn ($page) => $page->total() === 1);
});

test('customer listings cannot expose other customers orders or contacts', function () {
    $customer = dashboardUser(false);
    $other = dashboardUser(false);
    foreach ([$customer, $other] as $user) {
        $order = Order::create(['name' => 'Matching order', 'price' => 10, 'quantity' => 1, 'location' => 'Matching location']);
        $order->user()->attach($user);
        Contact::create(['title' => 'Matching contact', 'desc' => 'Matching message', 'email' => $user->email, 'user_id' => $user->id]);
    }
    $this->actingAs($customer);
    $this->get(route('offerCoupons'))->assertRedirect(route('home'));
    foreach (['orders', 'contacts'] as $resource) {
        Livewire::test(RecordTable::class, ['resource' => $resource])->set('search', 'Matching')
            ->assertViewHas('records', fn ($page) => $page->total() === 1);
    }
    Livewire::test(RecordTable::class, ['resource' => 'products'])->assertForbidden();
});

test('orders calculate decimal totals reserve stock and reject overselling without partial writes', function () {
    $this->actingAs(dashboardUser(false));
    $product = dashboardProduct();
    $data = ['name' => 'Customer order', 'location' => 'Test address', 'product_id' => $product->id, 'quantity' => 2, 'price' => 1];
    $this->post(route('order.store'), $data)->assertRedirect(route('order.index'));
    expect((float) Order::first()->price)->toBe(24.70);
    expect($product->fresh()->quantity)->toBe(8);
    $this->post(route('order.store'), [...$data, 'quantity' => 9])->assertSessionHasErrors('quantity');
    $this->assertDatabaseCount('orders', 1);
    expect($product->fresh()->quantity)->toBe(8);
});

test('order edits restore previous stock and cancellation restores the new product stock', function () {
    $customer = dashboardUser(false);
    $this->actingAs($customer);
    $old = dashboardProduct();
    $new = dashboardProduct(['name' => 'Replacement product', 'quantity' => 4, 'price' => 5.25]);
    $data = ['name' => 'Customer order', 'location' => 'Test address', 'product_id' => $old->id, 'quantity' => 2];
    $this->post(route('order.store'), $data)->assertSessionHasNoErrors();
    $order = Order::firstOrFail();
    $this->patch(route('order.update', $order), [...$data, 'quantity' => 9])->assertSessionHasNoErrors();
    expect($old->fresh()->quantity)->toBe(1);
    $this->patch(route('order.update', $order), [...$data, 'product_id' => $new->id, 'quantity' => 5])->assertSessionHasErrors('quantity');
    expect($old->fresh()->quantity)->toBe(1);
    expect($new->fresh()->quantity)->toBe(4);
    $this->patch(route('order.update', $order), [...$data, 'product_id' => $new->id, 'quantity' => 3])->assertRedirect(route('order.index'));
    expect($old->fresh()->quantity)->toBe(10);
    expect($new->fresh()->quantity)->toBe(1);
    expect((float) $order->fresh()->price)->toBe(15.75);
    expect($order->fresh()->user->modelKeys())->toBe([$customer->id]);
    $this->delete(route('order.destroy', $order))->assertRedirect(route('order.index'));
    expect($new->fresh()->quantity)->toBe(4);
});

test('customers cannot view modify or delete another customers records', function () {
    $owner = dashboardUser(false);
    $order = Order::create(['name' => 'Private order', 'price' => 10, 'quantity' => 1, 'location' => 'Private address']);
    $order->user()->attach($owner);
    $contact = Contact::create(['title' => 'Private contact', 'desc' => 'Private message', 'email' => $owner->email, 'user_id' => $owner->id]);
    $this->actingAs(dashboardUser(false));
    $this->get(route('order.show', $order))->assertForbidden();
    $this->get(route('order.edit', $order))->assertForbidden();
    $this->delete(route('order.destroy', $order))->assertForbidden();
    $this->get(route('contact.show', $contact))->assertForbidden();
    $this->patch(route('contact.update', $contact), [])->assertForbidden();
    $this->delete(route('contact.destroy', $contact))->assertForbidden();
});

test('offers save multiple associations and clear them when the type changes', function () {
    $this->actingAs(dashboardUser());
    $products = [dashboardProduct(), dashboardProduct()];
    $data = ['name' => 'Products sale', 'type' => 'products', 'products' => array_map(fn ($p) => $p->id, $products),
        'discount_type' => 'percentage', 'discount_value' => 15.5, 'start_date' => now()->subDay()->toDateString(), 'end_date' => now()->toDateString()];
    $this->post(route('offer.store'), $data)->assertRedirect(route('productsOffer'));
    $offer = Offer::firstOrFail();
    expect($offer->products)->toHaveCount(2);
    expect((float) $offer->discount_value)->toBe(0.155);
    expect(Offer::active()->count())->toBe(1);
    $category = Catagory::create(['name' => 'Sale category', 'desc' => 'Sale category description']);
    $this->patch(route('offer.update', $offer), [...$data, 'type' => 'categories', 'categories' => [$category->id]])
        ->assertRedirect(route('catagoryOffers'));
    expect($offer->fresh()->products)->toHaveCount(0);
    expect($offer->fresh()->categories->modelKeys())->toBe([$category->id]);
    $this->get(route('offer.show', $offer))->assertOk()->assertSee('Sale category');
    $this->patch(route('offer.update', $offer), [...$data, 'type' => 'global'])->assertRedirect(route('offer.index'));
    expect($offer->fresh()->categories)->toHaveCount(0);
});

test('offer validation rejects missing targets invalid ids and invalid percentages', function () {
    $this->actingAs(dashboardUser());
    $data = ['name' => 'Invalid sale', 'type' => 'products', 'discount_type' => 'percentage',
        'discount_value' => 101, 'start_date' => now()->toDateString(), 'end_date' => now()->toDateString()];
    $this->post(route('offer.store'), $data)->assertSessionHasErrors(['products', 'discount_value']);
    $this->post(route('offer.store'), [...$data, 'discount_value' => 10, 'products' => [999]])
        ->assertSessionHasErrors('products.0');
    $this->assertDatabaseCount('offers', 0);
});

test('product updates can clear tags and featured while retaining the existing image', function () {
    $this->actingAs(dashboardUser());
    $product = dashboardProduct();
    $tag = Tag::create(['name' => 'Coffee tag']);
    $product->tags()->attach($tag);
    $this->patch(route('product.update', $product), ['name' => 'Changed product', 'desc' => 'Changed description',
        'price' => 15.5, 'int_price' => 8, 'quantity' => 0])->assertRedirect(route('product.index'));
    expect($product->fresh()->tags)->toHaveCount(0);
    expect($product->fresh()->featured)->toBeFalse();
    expect($product->fresh()->image->path)->toBe('assets/images/test.png');
    expect((float) $product->fresh()->original_price)->toBe(15.5);
});

test('catalog filters combine and wishlist shows only the signed in customers products', function () {
    $user = dashboardUser(false);
    $this->actingAs($user);
    $category = Catagory::create(['name' => 'Coffee', 'desc' => 'Coffee category']);
    $wanted = dashboardProduct(['catagory_id' => $category->id]);
    dashboardProduct(['catagory_id' => $category->id, 'quantity' => 0]);
    $other = dashboardProduct();
    Livewire::test(Catalog::class)->set('search', 'Fresh')->set('categoryId', (string) $category->id)
        ->set('sort', 'price-desc')->assertViewHas('products', fn ($page) => $page->pluck('id')->all() === [$wanted->id]);
    $user->favoriates()->create(['product_id' => $wanted->id]);
    dashboardUser(false)->favoriates()->create(['product_id' => $other->id]);
    Livewire::test(Catalog::class, ['wishlist' => true])->assertViewHas('products', fn ($page) => $page->pluck('id')->all() === [$wanted->id]);
    Livewire::test('cards', ['products' => new \Illuminate\Database\Eloquent\Collection([$wanted]), 'products_offer' => new \Illuminate\Database\Eloquent\Collection, 'wishlist' => true])
        ->call('deleteFromFavoriate', $wanted->id)->assertDispatched('wishlist-updated')->assertDontSee('Coffee product');
    $this->assertDatabaseMissing('favoriates', ['user_id' => $user->id, 'product_id' => $wanted->id]);
});

test('admin middleware does not promote users based on their email', function () {
    $this->actingAs(User::factory()->create(['email' => 'yamen@gmail.com', 'role' => 'user']))
        ->get(route('product.index'))->assertRedirect(route('home'));
    expect(auth()->user()->fresh()->role)->toBe('user');
});

test('admin product company and offer forms render Select2 once', function (string $route) {
    $this->actingAs(dashboardUser());
    $product = dashboardProduct();
    $company = Company::create(['name' => 'Coffee company', 'desc' => 'Company description']);
    $company->products()->attach($product);
    $offer = Offer::create(['name' => 'Coffee sale', 'type' => 'products', 'discount_type' => 'fixed_amount',
        'discount_value' => 2, 'is_active' => true, 'start_date' => now()->subDay(), 'end_date' => now()->addDay()]);
    $offer->products()->attach($product);
    $record = match (strtok($route, '.')) { 'product' => $product, 'company' => $company, 'offer' => $offer };
    $response = $this->get(route($route, str_ends_with($route, '.edit') ? $record : []))->assertOk()
        ->assertSee('layout-content-navbar', false)->assertSee('data-user-select2', false)
        ->assertSee('/assets/js/admin.js', false)->assertDontSee('/assets/js/main.js', false);
    expect(substr_count($response->getContent(), '/select2/select2.js'))->toBe(1);
})->with(['product.create', 'product.edit', 'company.create', 'company.edit', 'offer.create', 'offer.edit']);

test('product edit retains validation input including cleared tags and checkbox', function () {
    $this->actingAs(dashboardUser());
    $product = dashboardProduct();
    $tag = Tag::create(['name' => 'Previous tag']);
    $product->tags()->attach($tag);
    $response = $this->withSession(['_old_input' => ['name' => 'Submitted name', 'desc' => 'Submitted description', 'featured' => false, 'tags' => []]])
        ->get(route('product.edit', $product))->assertOk()->assertSee('value="Submitted name"', false)
        ->assertSee('Submitted description')->assertDontSee('checked', false);
    preg_match('/<select\b[^>]*name="tags\[\]"[^>]*>(.*?)<\/select>/s', $response->getContent(), $tags);
    expect($tags[1])->not->toContain('selected');
});

test('admin contact create uses its actual fields and stays in the dashboard after saving', function () {
    $this->actingAs(dashboardUser());
    $this->get(route('contact.create'))->assertOk()->assertSee('name="title"', false)
        ->assertSee('name="email"', false)->assertDontSee('name="product_id"', false);
    $this->post(route('contact.store'), ['title' => 'New message', 'desc' => 'A contact message', 'email' => 'customer@example.com'])
        ->assertRedirect(route('contact.index'));
});

test('quick view renders its modal before the product loads and opens it after loading', function () {
    $product = dashboardProduct();
    Livewire::test('show')->assertSee('id="ec_quickview_modal"', false)
        ->call('loadProduct', $product->id)->assertDispatched('product-loaded')
        ->assertSee('Coffee product')->assertSee('$12.35');
});

test('adding products revives an expired cart and refreshes the shared cart data', function () {
    $user = dashboardUser(false);
    $product = dashboardProduct();
    $cart = Cart::create(['user_id' => $user->id]);
    $cart->forceFill(['created_at' => now()->subDays(2)])->save();
    $this->actingAs($user);
    expect(app(\App\Services\StorefrontData::class)->cart())->toBeNull();
    Livewire::test('add-to-cart', ['product' => $product])->call('addCart', $product->id)->assertDispatched('cart-updated');
    expect(app(\App\Services\StorefrontData::class)->cartCount())->toBe(1);
    $this->assertDatabaseCount('carts', 1);
});

test('public offer filters search active offers without exposing coupons', function () {
    foreach (['global', 'coupon', 'categories'] as $type) {
        Offer::create(['name' => 'Coffee sale', 'type' => $type, 'code' => $type === 'coupon' ? 'PRIVATE' : null,
            'discount_type' => 'fixed_amount', 'discount_value' => 2, 'is_active' => true,
            'start_date' => now()->subDay(), 'end_date' => now()->addDay()]);
    }
    Livewire::test(PublicOffers::class)->set('search', ' Coffee ')->set('type', 'categories')
        ->assertViewHas('offers', fn ($page) => $page->total() === 1 && $page->first()->type === 'categories')
        ->call('clearFilters')->assertViewHas('offers', fn ($page) => $page->total() === 2);
});
