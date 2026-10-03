<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === "en" ? 'ltr' : 'rtl' }}">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

  <title>@yield('title', $title ?? __('home.home'))</title>
  <meta name="keywords"
    content="apparel, catalog, clean, ecommerce, ecommerce HTML, electronics, fashion, html eCommerce, html store, minimal, multipurpose, multipurpose ecommerce, online store, responsive ecommerce template, shops" />
  <meta name="description" content="Best ecommerce html template for single and multi vendor store.">
  <meta name="author" content="ashishmaraviya">

  <!-- site Favicon -->
  <link rel="icon" href="{{ asset('assets/images/favicon/favicon.png') }}" sizes="32x32" />
  <link rel="apple-touch-icon" href="{{ asset('assets/images/favicon/favicon.png') }}" />
  <meta name="msapplication-TileImage" content="{{ asset('assets/images/favicon/favicon.png') }}" />

  <!-- css Icon Font -->
  <link rel="stylesheet" href="{{ asset('assets/css/vendor/ecicons.min.css')}}" />

  <!-- css All Plugins Files -->
  <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.css')}}" />
  <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper-bundle.min.css')}}" />
  <link rel="stylesheet" href="{{ asset('assets/css/plugins/jquery-ui.min.css')}}" />
  <link rel="stylesheet" href="{{ asset('assets/css/plugins/countdownTimer.css')}}" />
  <link rel="stylesheet" href="{{ asset('assets/css/plugins/slick.min.css')}}" />
  <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap.css')}}" />

  <!-- Main Style -->
  @if ($storefrontDemoStyles ?? true)
    <link rel="stylesheet" href="{{ asset('assets/css/demo1.css')}}" />
  @endif
  <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}" />
  <link rel="stylesheet" href="{{ asset('assets/css/responsive.css')}}" />
  <link rel="stylesheet" id="bg-switcher-css" href="{{ asset('assets/css/backgrounds/bg-4.css')}}">
  @livewireStyles
  @stack('styles')
</head>

<body>
  <!-- Feature tools end -->
  @yield('content')
  <!-- Vendor JS -->

  <script src="{{asset('assets/js/vendor/modernizr-3.11.2.min.js')}}"></script>
  <script src="{{asset('assets/js/vendor/jquery-3.5.1.min.js')}}"></script>
  <script src="{{asset('assets/js/vendor/jquery-migrate-3.3.0.min.js')}}"></script>
  <script src="{{asset('assets/js/vendor/popper.min.js')}}"></script>
  <script src="{{asset('assets/js/vendor/bootstrap.min.js')}}"></script>

  <script src="{{asset('assets/js/plugins/jquery.zoom.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/jquery.sticky-sidebar.js')}}"></script>
  <script src="{{asset('assets/js/vendor/jquery.magnific-popup.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/swiper-bundle.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/countdownTimer.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/scrollup.js')}}"></script>
  <script src="{{asset('assets/js/plugins/slick.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/infiniteslidev2.js')}}"></script>
  <script src="{{asset('assets/js/main.js')}}"></script>
  @livewireScripts
  @stack('scripts')
</body>

</html>
