<div class="ec-nav-toolbar">
  <div class="container">
    <div class="ec-nav-panel">
      <div class="ec-nav-panel-icons">
        <a href="#ec-mobile-menu" class="navbar-toggler-btn ec-header-btn ec-side-toggle"><i
            class="fi-rr-menu-burger"></i></a>
      </div>
      <div class="ec-nav-panel-icons">
        <a href="#ec-side-cart" class="toggle-cart ec-header-btn ec-side-toggle">
          <i class="fi-rr-shopping-bag"></i>
          <span class="ec-cart-noti ec-header-count cart-count-lable">
            {{ $cartCount }}
          </span>
        </a>
      </div>
      <div class="ec-nav-panel-icons">
        <a href="{{ route("home") }}" class="ec-header-btn"><i class="fi-rr-home"></i></a>
      </div>
      <div class="ec-nav-panel-icons">
        <a href="{{ route("wishlist") }}" class="ec-header-btn"><i class="fi-rr-heart"></i><span
            class="ec-cart-noti">{{ $favoriatesCount }}</span></a>
      </div>
      <div class="ec-nav-panel-icons">

        @guest
          <a href="{{ route("login") }}" class="ec-header-btn"><i class="fi-rr-user"></i></a>
        @endguest
        @if(auth()->check() && auth()->user()->role === "admin")
          <a href="{{ route("dashboard") }}" class="ec-header-btn"><i class="fi-rr-user"></i></a>
        @endif
      </div>

    </div>
  </div>
</div>
