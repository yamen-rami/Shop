<?php

use Illuminate\Support\Facades\Blade;

test('user layouts place page assets after shared dependencies', function (string $layout) {
    $html = Blade::render(<<<'BLADE'
        @extends($layout)
        @section('title', 'User layout test')
        @section('content')
            <main id="page-content">User content</main>
        @endsection
        @section('auth-content')
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

    if ($layout !== 'layouts.user-guest') {
        expect(strpos($html, 'id="page-js"'))->toBeGreaterThan(strpos($html, '/jquery'));
    }
})->with(['layouts.storefront', 'layouts.auth', 'layouts.user-guest']);

test('storefront loads select2 once after jquery', function () {
    $html = Blade::render(<<<'BLADE'
        @extends('layouts.storefront')
        @section('content', 'Storefront')
        BLADE);

    expect(substr_count($html, '/select2/select2.js'))->toBe(1);
    expect(strpos($html, '/select2/select2.js'))->toBeGreaterThan(strpos($html, '/jquery-3.5.1.min.js'));
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
