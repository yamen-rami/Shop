<?php

use Illuminate\Support\Facades\Blade;

test('user layouts place page assets after shared dependencies', function (string $layout) {
    $html = Blade::render(<<<'BLADE'
        @extends($layout)
        @section('title', 'User layout test')
        @section('content')
            <main id="page-content">User content</main>
        @endsection
        @push('styles')
            <link id="page-css" rel="stylesheet" href="{{ asset('assets/css/page-test.css') }}">
        @endpush
        @push('scripts')
            <script id="page-js" src="{{ asset('assets/js/page-test.js') }}"></script>
        @endpush
        BLADE, ['layout' => $layout]);

    expect($html)
        ->toContain('<title>User layout test</title>')
        ->toContain('<main id="page-content">User content</main>')
        ->not->toContain('@push');

    expect(strpos($html, 'id="page-css"'))->toBeLessThan(strpos($html, '</head>'));
    expect(strpos($html, 'id="page-js"'))->toBeGreaterThan(strpos($html, '</main>'));
    expect(strpos($html, 'id="page-js"'))->toBeLessThan(strpos($html, '</body>'));

    expect(strpos($html, 'id="page-js"'))->toBeGreaterThan(strpos($html, '/jquery'));
})->with(['layouts.storefront']);

test('storefront does not load page-specific select2 assets by default', function () {
    $html = Blade::render(<<<'BLADE'
        @extends('layouts.storefront')
        @section('content', 'Storefront')
        BLADE);

    expect($html)->not->toContain('/select2/select2.js', '/select2/select2.css', '/assets/css/auth.css');
});

test('every user page directly extends the storefront layout', function () {
    $directories = ['home', 'auth', 'profile', 'orders', 'contact', 'coupons', 'catagory_offer'];
    foreach ($directories as $directory) {
        foreach (glob(resource_path("views/$directory/*.blade.php")) as $path) {
            if (in_array(basename($path), ['page-start.blade.php', 'page-end.blade.php'])) {
                continue;
            }

            $source = file_get_contents($path);
            expect($source)->toContain("@extends('layouts.storefront'")
                ->not->toContain('<x-main-layout>', '<x-app-layout>', '<x-auth-layout>', '<x-guest-layout>', '<x-app>');
        }
    }
});

test('authentication pages render with the ekka template', function (string $path) {
    $this->get($path)
        ->assertOk()
        ->assertSee('/assets/css/style.css', false)
        ->assertSee('/assets/css/auth.css', false)
        ->assertSee('ec-login-container', false)
        ->assertSee('ec-breadcrumb', false)
        ->assertDontSee('/assets/vendor/css/core.css', false)
        ->assertDontSee('../../assets/', false);
})->with([
    '/login',
    '/register',
    '/forgot-password',
    '/reset-password/test-token?email=customer%40example.com',
]);

test('password update page requires authentication and uses the ekka template', function () {
    $this->get(route('password.edit'))->assertRedirect(route('login'));

    $user = \App\Models\User::factory()->create();
    $this->actingAs($user)->get(route('password.edit'))
        ->assertOk()
        ->assertSee('ec-login-container', false)
        ->assertSee('name="current_password"', false)
        ->assertSee('name="_method" value="PUT"', false);
});

test('login validation preserves email and renders accessible errors', function () {
    $this->from(route('login'))->post(route('login'), [
        'email' => 'customer@example.com',
        'password' => 'incorrect-password',
    ])->assertRedirect(route('login'))->assertSessionHasErrors('email');

    // Carry the browser's session cookie across the validation redirect.
    $this->withCookie(config('session.cookie'), session()->getId())
        ->get(route('login'))
        ->assertSee('value="customer@example.com"', false)
        ->assertSee('id="email-error"', false)
        ->assertSee('aria-describedby="email-error"', false)
        ->assertDontSee('value="incorrect-password"', false);
});

test('registration requires a matching password confirmation', function () {
    $this->post(route('register'), [
        'name' => 'Customer',
        'email' => 'customer@example.com',
        'password' => 'password',
        'password_confirmation' => 'different-password',
    ])->assertSessionHasErrors('password');

    $this->assertGuest();
});

test('password update displays its named validation errors', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user)->from(route('password.edit'))->put(route('password.update'), [
        'current_password' => 'wrong-password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])->assertRedirect(route('password.edit'))
        ->assertSessionHasErrorsIn('updatePassword', 'current_password');

    $this->withCookie(config('session.cookie'), session()->getId())
        ->get(route('password.edit'))
        ->assertSee('id="current_password-error"', false)
        ->assertDontSee('value="wrong-password"', false);
});

test('signed-in user listings and forms render the shared layout', function (string $viewName) {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);
    // Direct view rendering bypasses the middleware that normally shares errors.
    view()->share('errors', new \Illuminate\Support\ViewErrorBag);
    $emptyPage = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 30);
    $order = (new \App\Models\Order)->forceFill([
        'id' => 1, 'name' => 'Test order', 'price' => 10, 'quantity' => 1, 'location' => 'Test address',
    ])->setRelation('products', collect())->setRelation('user', collect([$user]));
    $contact = (new \App\Models\Contact)->forceFill([
        'id' => 1, 'title' => 'Test contact', 'desc' => 'Test message', 'email' => $user->email,
    ])->setRelation('user', $user);

    $html = view($viewName, [
        'products' => collect(),
        'orders' => $emptyPage,
        'contacts' => $emptyPage,
        'offers' => $emptyPage,
        'order' => $order,
        'contact' => $contact,
    ])->render();

    expect($html)->toContain('/assets/css/style.css', 'ec-header')
        ->not->toContain('layout-content-navbar');

    if (in_array($viewName, ['orders.create', 'orders.edit', 'contact.create'])) {
        expect(substr_count($html, '/select2/select2.js'))->toBe(1);
        expect(strpos($html, '/select2/select2.js'))->toBeGreaterThan(strpos($html, '/jquery-3.5.1.min.js'));
        expect(strpos($html, '/user-selects.js'))->toBeGreaterThan(strpos($html, '/select2/select2.js'));
        expect(strpos($html, '/select2/select2.css'))->toBeLessThan(strpos($html, '</head>'));
    } else {
        expect($html)->not->toContain('/select2/select2.js');
    }
})->with([
    'orders.index', 'orders.create', 'orders.edit', 'orders.show',
    'contact.index', 'contact.create', 'contact.edit', 'contact.show',
    'coupons.index', 'catagory_offer.index',
]);

test('admin dashboard keeps its existing layout', function () {
    $admin = \App\Models\User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();

    $this->actingAs($admin)->get(route('dashboard'))
        ->assertOk()
        ->assertSee('layout-content-navbar', false)
        ->assertSee('/assets/vendor/css/core.css', false)
        ->assertDontSee('/assets/css/auth.css', false);
});
