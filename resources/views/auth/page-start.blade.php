<x-loader />
    <x-home.navbar />
    <div class="ec-side-cart-overlay"></div>
    <div id="ec-side-cart" class="ec-side-cart">
        <div class="ec-cart-inner">
            <div class="ec-cart-top">
                <div class="ec-cart-title">
                    <span class="cart_title">{{ __('My Cart') }}</span>
                    <button type="button" class="ec-close" aria-label="{{ __('Close cart') }}">&times;</button>
                </div>
                <p>{{ auth()->check() ? __('View your cart and complete your order at checkout.') : __('Log in to view your cart.') }}</p>
            </div>
            <div class="ec-cart-bottom">
                <a href="{{ auth()->check() ? route('checkout') : route('login') }}" class="btn btn-primary">
                    {{ auth()->check() ? __('Checkout') : __('Login') }}
                </a>
            </div>
        </div>
    </div>
    <div class="sticky-header-next-sec ec-breadcrumb section-space-mb">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row ec_breadcrumb_inner">
                        <div class="col-md-6 col-sm-12">
                            <h2 class="ec-breadcrumb-title">@yield('title')</h2>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <ul class="ec-breadcrumb-list">
                                <li class="ec-breadcrumb-item"><a href="{{ route('home') }}">{{ __('home.home') }}</a></li>
                                <li class="ec-breadcrumb-item active" aria-current="page">@yield('title')</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="ec-page-content section-space-p">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="section-title">
                        <h2 class="ec-bg-title">@yield('title')</h2>
                        <h2 class="ec-title">@yield('title')</h2>
                        <p class="sub-title mb-3">@yield('auth-description', __('Best place to buy and sell digital products'))</p>
                    </div>
                </div>
                <div class="ec-login-wrapper">
                    <div class="ec-login-container">
                        <div class="ec-login-form">
                            @if (session('status'))
                                <p class="alert alert-success" role="status">{{ session('status') === 'password-updated' ? __('Your password has been updated.') : session('status') }}</p>
                            @endif
