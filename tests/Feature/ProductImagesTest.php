<?php

use App\Models\{Color, Product, User};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function imageProductData(): array
{
    return ['name' => 'Gallery product', 'desc' => 'A product with several photos',
        'price' => 30, 'int_price' => 15, 'quantity' => 10];
}

test('creating a product saves every uploaded image with its own color and selects the first', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $red = Color::create(['name' => 'Red']);
    $blue = Color::create(['name' => 'Blue']);

    $this->post(route('product.store'), [...imageProductData(), 'image_count' => 2, 'images' => [
        ['file' => UploadedFile::fake()->image('front.jpg'), 'color_id' => $red->id],
        ['file' => UploadedFile::fake()->image('back.jpg'), 'color_id' => $blue->id],
    ]])->assertSessionHasNoErrors()->assertRedirect(route('product.index'));

    $product = Product::with('image', 'images.colors')->firstOrFail();
    expect($product->images)->toHaveCount(2);
    expect($product->image->is($product->images->first()))->toBeTrue();
    expect($product->images->pluck('color_id')->all())->toBe([$red->id, $blue->id]);
    foreach ($product->images as $image) {
        expect($image->imageable->is($product))->toBeTrue();
        expect($image->product_id)->toBe($product->id);
        Storage::disk('public')->assertExists($image->path);
    }

    foreach ([route('showProduct', $product), route('product.show', $product)] as $url) {
        $response = $this->get($url)->assertOk()->assertSee('product-gallery-thumbnails')->assertSee('Red')->assertSee('Blue');
        foreach ($product->images as $image) {
            $response->assertSee(asset('storage/'.$image->path));
        }
    }
    $this->get(route('products'))->assertOk()->assertSee(asset('storage/'.$product->image->path));
    $this->get(route('product.index'))->assertOk()->assertSee(asset('storage/'.$product->image->path));
});

test('product image validation rejects count mismatches missing colors and invalid uploads', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $red = Color::create(['name' => 'Red']);
    $this->post(route('product.store'), [...imageProductData(), 'image_count' => 2, 'images' => [
        ['file' => UploadedFile::fake()->image('front.jpg'), 'color_id' => $red->id],
    ]])->assertSessionHasErrors('images');
    $this->post(route('product.store'), [...imageProductData(), 'image_count' => 1, 'images' => [
        ['file' => UploadedFile::fake()->create('document.pdf'), 'color_id' => 99999],
    ]])->assertSessionHasErrors(['images.0.file', 'images.0.color_id']);
    $this->post(route('product.store'), [...imageProductData(), 'image_count' => 1, 'images' => [
        ['file' => UploadedFile::fake()->image('front.jpg')],
    ]])->assertSessionHasErrors('images.0.color_id');
    $this->assertDatabaseCount('products', 0);
    expect(Storage::disk('public')->allFiles())->toBeEmpty();
});

test('replacing the main image retains its position and every other gallery image', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $red = Color::create(['name' => 'Red']);
    $blue = Color::create(['name' => 'Blue']);
    $product = Product::create([...imageProductData(), 'original_price' => 30]);
    Storage::disk('public')->put('products/front.jpg', 'old front');
    Storage::disk('public')->put('products/back.jpg', 'old back');
    $main = $product->images()->create(['path' => 'products/front.jpg', 'color_id' => $red->id]);
    $other = $product->images()->create(['path' => 'products/back.jpg', 'color_id' => $blue->id]);

    $this->patch(route('product.update', $product), [...imageProductData(),
        'image' => UploadedFile::fake()->image('replacement.jpg'), 'color_id' => $blue->id,
    ])->assertSessionHasNoErrors()->assertRedirect(route('product.index'));

    expect($product->fresh()->images)->toHaveCount(2);
    expect($product->fresh()->image->id)->toBe($main->id);
    expect($main->fresh()->color_id)->toBe($blue->id);
    expect($other->fresh()->path)->toBe('products/back.jpg');
    Storage::disk('public')->assertMissing('products/front.jpg');
    Storage::disk('public')->assertExists($main->fresh()->path);
    Storage::disk('public')->assertExists('products/back.jpg');

    $this->delete(route('product.destroy', $product))->assertRedirect();
    $this->assertDatabaseCount('images', 0);
    expect(Storage::disk('public')->allFiles())->toBeEmpty();
});

test('products without images render a placeholder and image relation respects the morph owner', function () {
    $product = Product::create([...imageProductData(), 'original_price' => 30]);
    $color = Color::create(['name' => 'Red']);
    \App\Models\Image::create(['path' => 'other.jpg', 'color_id' => $color->id,
        'imageable_type' => \App\Models\Company::class, 'imageable_id' => $product->id]);
    expect($product->image)->toBeNull();
    expect($product->images)->toBeEmpty();
    $this->get(route('showProduct', $product))->assertOk()->assertSee('image-placeholder.svg')->assertSee('No photos yet');
});

test('edit shows every current image and can replace one change its color and append new photos', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $red = Color::create(['name' => 'Red']);
    $blue = Color::create(['name' => 'Blue']);
    $product = Product::create([...imageProductData(), 'original_price' => 30]);
    Storage::disk('public')->put('products/main.jpg', 'main photo');
    Storage::disk('public')->put('products/second.jpg', 'second photo');
    $main = $product->images()->create(['path' => 'products/main.jpg', 'color_id' => $red->id]);
    $second = $product->images()->create(['path' => 'products/second.jpg', 'color_id' => $red->id]);

    $this->get(route('product.edit', $product))->assertOk()->assertSee('Current images')->assertSee('Add more images')
        ->assertSee('name="existing_images['.$main->id.'][file]"', false)
        ->assertSee('name="existing_images['.$second->id.'][file]"', false)
        ->assertSee(asset('storage/products/main.jpg'))->assertSee(asset('storage/products/second.jpg'));

    $this->patch(route('product.update', $product), [...imageProductData(), 'image_count' => 1,
        'existing_images' => [
            $main->id => ['color_id' => $red->id],
            $second->id => ['file' => UploadedFile::fake()->image('new-side.jpg'), 'color_id' => $blue->id],
        ],
        'images' => [['file' => UploadedFile::fake()->image('detail.jpg'), 'color_id' => $blue->id]],
    ])->assertSessionHasNoErrors()->assertRedirect(route('product.index'));

    expect($product->fresh()->images)->toHaveCount(3);
    expect($product->fresh()->image->id)->toBe($main->id);
    expect($main->fresh()->path)->toBe('products/main.jpg');
    expect($second->fresh()->color_id)->toBe($blue->id);
    Storage::disk('public')->assertMissing('products/second.jpg');
    foreach ($product->fresh()->images as $image) {
        Storage::disk('public')->assertExists($image->path);
    }
});

test('removing the main image selects the next image and deletes only the removed file', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $color = Color::create(['name' => 'Red']);
    $product = Product::create([...imageProductData(), 'original_price' => 30]);
    foreach (['main', 'second'] as $name) {
        Storage::disk('public')->put('products/'.$name.'.jpg', $name);
        $product->images()->create(['path' => 'products/'.$name.'.jpg', 'color_id' => $color->id]);
    }
    [$main, $second] = $product->images;
    $this->patch(route('product.update', $product), [...imageProductData(),
        'existing_images' => [$main->id => ['color_id' => $color->id, 'remove' => true]],
    ])->assertSessionHasNoErrors()->assertRedirect(route('product.index'));
    expect($product->fresh()->image->id)->toBe($second->id);
    expect($product->fresh()->images)->toHaveCount(1);
    Storage::disk('public')->assertMissing('products/main.jpg');
    Storage::disk('public')->assertExists('products/second.jpg');
});

test('image edits reject another products image removing the last photo and missing new uploads', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $color = Color::create(['name' => 'Red']);
    $product = Product::create([...imageProductData(), 'original_price' => 30]);
    $image = $product->images()->create(['path' => 'main.jpg', 'color_id' => $color->id]);
    $other = Product::create([...imageProductData(), 'original_price' => 30]);
    $foreignImage = $other->images()->create(['path' => 'other.jpg', 'color_id' => $color->id]);

    $this->patch(route('product.update', $product), [...imageProductData(),
        'existing_images' => [$foreignImage->id => ['color_id' => $color->id, 'remove' => true]],
    ])->assertSessionHasErrors('existing_images');
    $this->patch(route('product.update', $product), [...imageProductData(),
        'existing_images' => [$image->id => ['color_id' => $color->id, 'remove' => true]],
    ])->assertSessionHasErrors('existing_images');
    $this->patch(route('product.update', $product), [...imageProductData(), 'image_count' => 2])
        ->assertSessionHasErrors('images');
    $this->assertDatabaseCount('images', 2);
});
