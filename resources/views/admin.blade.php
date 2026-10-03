<!doctype html>

<html lang="{{ app()->getLocale() }}" class=" layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr"
  data-skin="default" data-bs-theme="light" data-assets-path="{{ asset('assets') }}/"
  data-template="vertical-menu-template-starter">

<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <meta name="robots" content="noindex, nofollow" />
  <title>@yield('title', 'Vuexy dashboard')</title>
  <meta name="description" content="" />
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />
  <script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}" />
  <script src="{{ asset('assets/js/config.js') }}"></script>
  @livewireStyles
  @stack('styles')

</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar  ">
    <div class="layout-container">
      <!-- Menu -->

      <aside id="layout-menu" class="layout-menu menu-vertical menu">
        <div class="app-brand demo ">
          <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
              <span class="text-primary">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                    fill="currentColor" />
                  <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                    d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                  <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                    d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                  <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                    fill="currentColor" />
                </svg>
              </span>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-3">Vuexy</span>
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Page -->
          <li @class(['menu-item', 'active' => request()->routeIs('dashboard')])>
            <a href="{{ route('dashboard') }}" class="menu-link">
              <i class="menu-icon icon-base ti tabler-smart-home"></i>
              <div>{{ __('dashboard.title') }}</div>
            </a>
          </li>
          <li @class(['menu-item', 'active' => request()->routeIs('catagory.*', 'getProducts')])>
            <a href="{{ route('catagory.index') }}" class="menu-link">
              <i class="menu-icon icon-base ti tabler-category"></i>
              <div>{{ __("dashboard.category") }}</div>
            </a>
          </li>
          <li @class(['menu-item', 'active' => request()->routeIs('product.*')])>
            <a href="{{ route('product.index') }}" class="menu-link">
              <i class="menu-icon icon-base ti tabler-package"></i>
              <div>{{ __("dashboard.products") }}</div>
            </a>
          </li>
          <li @class(['menu-item', 'active' => request()->routeIs('company.*')])>
            <a href="{{ route('company.index') }}" class="menu-link">
              <i class="menu-icon icon-base ti tabler-building-store"></i>
              <div>{{ __("dashboard.companies") }}</div>
            </a>
          </li>
          <li @class(['menu-item', 'active' => request()->routeIs('order.*')])>
            <a href="{{ route('order.index') }}" class="menu-link">
              <i class="menu-icon icon-base ti tabler-shopping-cart"></i>
              <div>{{ __("dashboard.orders") }}</div>
            </a>
          </li>
          <li @class(['menu-item', 'active' => request()->routeIs('offer.*')])>
            <a href="{{ route('offer.index') }}" class="menu-link">
              <i class="menu-icon icon-base ti tabler-discount"></i>
              <div>{{ __("dashboard.offers") }}</div>
            </a>
          </li>
          <li @class(['menu-item', 'active' => request()->routeIs('productsOffer')])>
            <a href="{{ route('productsOffer') }}" class="menu-link">
              <i class="menu-icon icon-base ti tabler-gift"></i>
              <div>{{ __("dashboard.productsOffers") }}</div>
            </a>
          </li>
          <li @class(['menu-item', 'active' => request()->routeIs('offerCoupons')])>
            <a href="{{ route('offerCoupons') }}" class="menu-link">
              <i class="menu-icon icon-base ti tabler-ticket"></i>
              <div>{{ __("dashboard.couponOffers") }}</div>
            </a>
          </li>
          <li @class(['menu-item', 'active' => request()->routeIs('catagoryOffers')])>
            <a href="{{ route('catagoryOffers') }}" class="menu-link">
              <i class="menu-icon icon-base ti tabler-percentage"></i>
              <div>
                {{ __("dashboard.categoriesOffers") }}
              </div>
            </a>
          </li>
          <li @class(['menu-item', 'active' => request()->routeIs('contact.*')])>
            <a href="{{ route('contact.index') }}" class="menu-link">
              <i class="menu-icon icon-base ti tabler-mail"></i>
              <div>{{ __("home.contact us") }}</div>
            </a>
          </li>
          <li @class(['menu-item', 'active' => request()->routeIs('tag.*')])>
            <a href="{{ route('tag.index') }}" class="menu-link">
              <i class="menu-icon icon-base ti tabler-tags"></i>
              <div>{{ __("dashboard.tags") }}</div>
            </a>
          </li>
        </ul>
      </aside>

      <div class="menu-mobile-toggler d-xl-none rounded-1">
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
          <i class="ti tabler-menu icon-base"></i>
          <i class="ti tabler-chevron-right icon-base"></i>
        </a>
      </div>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <nav
          class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0   d-xl-none ">
            <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
              <i class="icon-base ti tabler-menu-2 icon-md"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
            <div class="navbar-nav align-items-center">
              <div class="nav-item dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);"
                  data-bs-toggle="dropdown">
                  <i class="icon-base ti tabler-sun icon-md theme-icon-active"></i>
                  <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="nav-theme-text">
                  <li>
                    <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light"
                      aria-pressed="false">
                      <span><i class="icon-base ti tabler-sun icon-md me-3" data-icon="sun"></i>Light</span>
                    </button>
                  </li>
                  <li>
                    <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark"
                      aria-pressed="true">
                      <span><i class="icon-base ti tabler-moon-stars icon-md me-3"
                          data-icon="moon-stars"></i>Dark</span>
                    </button>
                  </li>
                  <li>
                    <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system"
                      aria-pressed="false">
                      <span><i class="icon-base ti tabler-device-desktop-analytics icon-md me-3"
                          data-icon="device-desktop-analytics"></i>System</span>
                    </button>
                  </li>
                </ul>
              </div>
            </div>

            <ul class="navbar-nav flex-row align-items-center ms-md-auto">
              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                          <small class="text-body-secondary">{{ auth()->user()->role }}</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider my-1 mx-n2"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                      <i class="icon-base ti tabler-user icon-md me-3"></i><span>My Profile</span>
                    </a>
                  </li>
                  <li><a class="dropdown-item" href="{{ route('home') }}"><i class="icon-base ti tabler-shopping-cart icon-md me-3"></i>Storefront</a></li>
                  <li>
                    <div class="dropdown-divider my-1 mx-n2"></div>
                  </li>
                  <li>
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="dropdown-item"><i class="icon-base ti tabler-power icon-md me-3"></i>Log Out</button>
                    </form>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            @hasSection('header')
              <h1 class="fs-5 mb-5">@yield('header')</h1>
            @endif
            <!-- DataTable with Buttons -->
            @yield('content')
            <!--/ DataTable with Buttons -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
  </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js')}}"></script>

    <script src="{{ asset('assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js')}}"></script>

    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js')}}"></script>

    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js')}}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js')}}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <!-- Flat Picker -->
    <!-- Form Validation -->


    <!-- Main JS -->


    <!-- Page JS -->

    <script src="{{ asset('assets/js/admin.js')}}"></script>

    @livewireScripts
    @stack('scripts')
</body>

</html>
