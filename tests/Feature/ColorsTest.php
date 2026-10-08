<?php

use App\Models\{Color, Product, User};
use Database\Seeders\ColorSeeder;

test('administrators can manage colors and new colors appear in Select2 searches', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $this->get(route('color.create'))->assertOk()->assertSee('Color name');
    $this->post(route('color.store'), ['name' => 'Ocean Blue'])->assertRedirect(route('color.index'));
    $color = Color::firstOrFail();
    $this->get(route('color.index'))->assertOk()->assertSee('Ocean Blue');
    $this->getJson(route('color.options', ['q' => 'Ocean']))->assertOk()
        ->assertJsonPath('results.0.id', $color->id)->assertJsonPath('results.0.text', 'Ocean Blue');
    $this->get(route('color.edit', $color))->assertOk()->assertSee('Ocean Blue');
    $this->patch(route('color.update', $color), ['name' => 'Midnight Blue'])->assertRedirect(route('color.index'));
    $this->getJson(route('color.options', ['ids' => [$color->id]]))->assertOk()->assertJsonPath('results.0.text', 'Midnight Blue');
    $this->post(route('color.store'), ['name' => 'Midnight Blue'])->assertSessionHasErrors('name');
    $this->delete(route('color.destroy', $color))->assertRedirect(route('color.index'));
    $this->assertDatabaseCount('colors', 0);
});

test('colors used by images cannot be deleted', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $color = Color::create(['name' => 'Red']);
    $product = Product::create(['name' => 'Red jacket', 'desc' => 'A red jacket', 'price' => 30,
        'int_price' => 10, 'original_price' => 30, 'quantity' => 5]);
    $product->images()->create(['path' => 'jacket.jpg', 'color_id' => $color->id]);
    $this->from(route('color.index'))->delete(route('color.destroy', $color))->assertSessionHasErrors('color');
    $this->assertDatabaseCount('colors', 1);
    $this->assertDatabaseCount('images', 1);
});

test('color Select2 supports paging search and selected option lookup', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    foreach (range(1, 25) as $number) {
        Color::create(['name' => sprintf('Color %02d', $number)]);
    }
    $this->getJson(route('color.options'))->assertOk()->assertJsonCount(20, 'results')->assertJsonPath('pagination.more', true);
    $this->getJson(route('color.options', ['page' => 2]))->assertOk()->assertJsonCount(5, 'results')->assertJsonPath('pagination.more', false);
    $this->getJson(route('color.options', ['q' => 'Color 25']))->assertOk()->assertJsonCount(1, 'results');
    $this->getJson(route('color.options', ['ids' => range(1, 21)]))->assertUnprocessable();
});

test('color management and lookups require administrator access', function () {
    $this->getJson(route('color.options'))->assertUnauthorized();
    $this->actingAs(User::factory()->create(['role' => 'user']));
    $this->getJson(route('color.options'))->assertRedirect(route('home'));
    $this->post(route('color.store'), ['name' => 'Red'])->assertRedirect(route('home'));
    $this->assertDatabaseCount('colors', 0);
});

test('the color seeder populates available colors without duplicating custom colors on reruns', function () {
    Color::create(['name' => 'Custom color']);
    $this->seed(ColorSeeder::class);
    $count = Color::count();
    $this->seed(ColorSeeder::class);
    expect(Color::count())->toBe($count);
    expect(Color::where('name', 'Custom color')->exists())->toBeTrue();
    expect(Color::whereIn('name', ['Red', 'Blue', 'Sage', 'Multicolor'])->count())->toBe(4);
});
