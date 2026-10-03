<header class="ec-header">
  <!--Ec Header Top Start -->
  <div class="header-top">
    <div class="container">
      <div class="row align-items-center">
        <!-- Header Top social Start -->
        <div class="col text-left header-top-left d-none d-lg-block">
          <div class="header-top-social">
            <span class="social-text text-upper">Follow us on:</span>
            <ul class="mb-0 ">
              <li class="text-start"><a class="hdr-facebook" href="https://www.facebook.com/yamen.rami.abuwarda"><i class="ecicon eci-facebook"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <!-- Header Top social End -->
        <!-- Header Top Message Start -->
        <!-- Header Top Message End -->
        <!-- Header Top Language Currency -->
        <div class="col header-top-right d-none d-lg-block">
          <div class="header-top-lan-curr d-flex justify-content-end">
            <!-- Currency Start -->
            <div class="header-top-curr dropdown">
              <button class="dropdown-toggle text-upper" data-bs-toggle="dropdown">{{ __("home.currency") }} <i
                  class="ecicon eci-caret-down" aria-hidden="true"></i></button>
              <ul class="dropdown-menu">
                <li class="active"><a class="dropdown-item" href="#">USD $</a></li>
                <li><a class="dropdown-item" href="#">EUR €</a></li>
              </ul>
            </div>
            <!-- Currency End -->
            <!-- Language Start -->
            <div class="header-top-lan dropdown">
              <button class="dropdown-toggle text-upper" data-bs-toggle="dropdown">{{ __("home.lang") }} <i
                  class="ecicon eci-caret-down" aria-hidden="true"></i></button>
              <ul class="dropdown-menu">
                <li class="active"><a class="dropdown-item" href="locale/en">English</a></li>
                <li><a class="dropdown-item" href="locale/ar">Arabic</a></li>
              </ul>
            </div>
            <!-- Language End -->

          </div>
        </div>
        <!-- Header Top Language Currency -->
        <!-- Header Top responsive Action -->
        <div class="col d-lg-none ">
          <div class="ec-header-bottons">
            <!-- Header User Start -->
            <div class="ec-header-user dropdown">
              <button class="dropdown-toggle ml-3" data-bs-toggle="dropdown"><i class="fi-rr-user"></i></button>
              <ul class="dropdown-menu dropdown-menu-right">
                @guest
                  {{-- ! MOBILE --}}
                  <li><a class="dropdown-item" href="{{ route("register") }}">{{ __("home.register") }}</a></li>
                  <li><a class="dropdown-item" href="{{ route("login") }}">{{ __("home.login") }}</a></li>
                @endguest
                @auth
                  <form method="post" action="{{ route("logout") }}">
                    @csrf
                    <li><a class="dropdown-item">{{ __('home.logout') }}</a></li>
                  </form>
                @endauth
              </ul>
            </div>
            <!-- Header User End -->
            <!-- Header Cart Start -->
            <a href="{{ route("wishlist") }}" class="ec-header-btn ec-header-wishlist">
              <div class="header-icon"><i class="fi-rr-heart"></i></div>
              <span class="ec-header-count"></span>
            </a>
            <!-- Header Cart End -->
            <!-- Header Cart Start -->
            <a href="#ec-side-cart" class="ec-header-btn ec-side-toggle">
              <div class="header-icon"><i class="fi-rr-shopping-bag"></i></div>
              <span class="ec-header-count cart-count-lable">
                {{ $cartCount }}
              </span>
            </a>
            <!-- Header Cart End -->
            <a href="javascript:void(0)" class="ec-header-btn ec-sidebar-toggle">
              <i class="fi fi-rr-apps"></i>
            </a>
            <!-- Header menu Start -->
            <a href="#ec-mobile-menu" class="ec-header-btn ec-side-toggle d-lg-none">
              <i class="fi fi-rr-menu-burger"></i>
            </a>
            <!-- Header menu End -->
          </div>
        </div>
        <!-- Header Top responsive Action -->
      </div>
    </div>
  </div>
  <!-- Ec Header Top  End -->
  <!-- Ec Header Bottom  Start -->
  <div class="ec-header-bottom d-none d-lg-block">
    <div class="container position-relative">
      <div class="row">
        <div class="ec-flex">
          <!-- Ec Header Logo Start -->
          <div class="align-self-center">
            <div class="header-logo">
              <a href="{{ route("home") }}"><img src="{{asset("assets/images/logo/logo.png")}}" alt="Site Logo" /><img
                  class="dark-logo" src="assets/images/logo/dark-logo.png" alt="Site Logo" style="display: none;" /></a>
            </div>
          </div>
          <!-- Ec Header Logo End -->

          <!-- Ec Header Search Start -->
          {{-- <div class="align-self-center">
            <div class="header-search">
              <form class="ec-btn-group-form" action="{{ route(" products") }}" method="get">
                <input class="form-control ec-search-bar" name="search" placeholder="Search products..." type="text">
                <button class="submit" type="submit"><i class="fi-rr-search"></i></button>
              </form>
            </div>
          </div> --}}
          <livewire:search />
          <!-- Ec Header Search End -->

          <!-- Ec Header Button Start -->
          <div class="align-self-center">
            <div class="ec-header-bottons">

              <!-- Header User Start -->
              <div class="ec-header-user dropdown">
                <button class="dropdown-toggle  ml-5"  data-bs-toggle="dropdown"><i class="fi-rr-user"></i></button>
                {{--
                <livewire:count /> --}}
                <ul class="dropdown-menu dropdown-menu-right">
                  @guest
                    {{-- ! LAPTOP --}}
                    <li><a class="dropdown-item" href="{{ route("register") }}">{{ __("home.register") }}</a></li>
                    <li><a class="dropdown-item" href="{{ route("login") }}">{{ __("home.login") }}</a></li>
                  @endguest
                  @auth
                    @if(auth()->user()->role == "admin")
                      <li><a class="dropdown-item" href="{{ route("dashboard") }}">{{ __("home.admin") }}</a></li>
                    @endif
                  @endauth
                  @auth
                    <form method="post" action="{{ route("logout") }}">
                      @csrf
                      <button><a class="dropdown-item">{{ __("home.logout") }}</a></button>
                    </form>
                  @endauth
                </ul>
              </div>
              <!-- Header User End -->
              <!-- Header wishlist Start -->
              <a href="{{ route("wishlist") }}" class="ec-header-btn ec-header-wishlist">
                <div class="header-icon"><i class="fi-rr-heart"></i>

                </div>
                <span class="ec-header-count">
                  @if(auth()->check())
                    {{ auth()->user()->favoriates()->count() }}
                  @else
                    0
                  @endif</span>
              </a>
              <!-- Header wishlist End -->
              <!-- Header Cart Start -->
              <a href="#ec-side-cart" class="ec-header-btn ec-side-toggle">
                <div class="header-icon"><i class="fi-rr-shopping-bag"></i></div>
                <span class="ec-header-count">
                  <livewire:count />
                  {{-- {{ $cartCount }} --}}
                </span>
              </a>
              <!-- Header Cart End -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Ec Header Button End -->
  <!-- Header responsive Bottom  Start -->
  <div class="ec-header-bottom d-lg-none">
    <div class="container position-relative">
      <div class="row ">

        <!-- Ec Header Logo Start -->
        <div class="col">
          <div class="header-logo">
            <a href="index.html"><img src="{{ asset('assets/images/logo/logo.png') }}" alt="Site Logo" /><img class="dark-logo"
                src="assets/images/logo/dark-logo.png" alt="Site Logo" style="display: none;" /></a>
          </div>
        </div>
        <!-- Ec Header Logo End -->
        <!-- Ec Header Search Start -->
        <div class="col">
          <div class="header-search">
            <livewire:search></livewire:search>
          </div>
        </div>
        <!-- Ec Header Search End -->
      </div>
    </div>
  </div>
  <!-- Header responsive Bottom  End -->
  <!-- EC Main Menu Start -->
  <div id="ec-main-menu-desk" class="d-none d-lg-block sticky-nav">
    <div class="container position-relative">
      <div class="row">
        <div class="col-md-12 align-self-center">
          <div class="ec-main-menu">
            <a href="javascript:void(0)" class="ec-header-btn ec-sidebar-toggle">
              <i class="fi fi-rr-apps"></i>
            </a>
            <ul>
              <li><a href="{{ route("home") }}">{{ __("home.home") }}</a></li>
              <li><a href="{{ route("products") }}">{{ __("home.products") }}</a></li>
              <li><a href="{{ route('home.offers') }}">{{ __("home.offers") }}</a></li>
              <li><a href="{{ route('contact.create') }}">{{ __("home.contact us") }}</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Ec Main Menu End -->
  <!-- ekka Mobile Menu Start -->
  <div id="ec-mobile-menu" class="ec-side-cart ec-mobile-menu">
    <div class="ec-menu-title">
      <span class="menu_title">My Menu</span>
      <button class="ec-close">×</button>
    </div>
    <div class="ec-menu-inner">
      <div class="ec-menu-content">
        <ul>
          <li><a href="{{ route("home") }}">{{ __("home.home") }}</a></li>
          <li><a href="{{ route("products") }}">{{ __("home.products") }}</a></li>
          <li><a href="{{ route('home.offers') }}">{{ __("home.offers") }}</a></li>
          <li><a href="{{ route('contact.create') }}">{{ __("home.contact us") }}</a></li>
          @guest
            <li><a href="{{ route("login") }}">{{ __("home.login") }}</a></li>
            <li><a href="{{ route("register") }}">{{ __("home.register") }}</a></li>
          @endguest
          </li>

        </ul>
      </div>
      <div class="header-res-lan-curr">
        <div class="header-top-lan-curr">
          <!-- Language Start -->
          <div class="header-top-lan dropdown">
            <button class="dropdown-toggle text-upper" data-bs-toggle="dropdown">Language <i
                class="ecicon eci-caret-down" aria-hidden="true"></i></button>
            <ul class="dropdown-menu">
              <li class="active"><a class="dropdown-item" href="locale/en">English</a></li>
              <li><a class="dropdown-item" href="locale/ar">Arabic</a></li>
            </ul>
          </div>
          <!-- Language End -->
          <!-- Currency Start -->
          <div class="header-top-curr dropdown">
            <button class="dropdown-toggle text-upper" data-bs-toggle="dropdown">{{ __("home.currency") }} <i
                class="ecicon eci-caret-down" aria-hidden="true"></i></button>
            <ul class="dropdown-menu">
              <li class="active"><a class="dropdown-item" href="#">USD $</a></li>
              <li><a class="dropdown-item" href="#">EUR €</a></li>
            </ul>
          </div>
          <!-- Currency End -->
        </div>
        <!-- Social Start -->
        <div class="header-res-social">
          <div class="header-top-social">
            <ul class="mb-0">
              <li class="list-inline-item"><a class="hdr-facebook" href="https://www.facebook.com/yamen.rami.abuwarda"><i class="ecicon eci-facebook"></i></a>
              </li>
              <li class="list-inline-item"><a class="hdr-twitter" href="#"><i class="ecicon eci-twitter"></i></a></li>
              <li class="list-inline-item"><a class="hdr-instagram" href="#"><i class="ecicon eci-instagram"></i></a>
              </li>
              <li class="list-inline-item"><a class="hdr-linkedin" href="#"><i class="ecicon eci-linkedin"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <!-- Social End -->
      </div>
    </div>
  </div>
  <!-- ekka mobile Menu End -->
</header>