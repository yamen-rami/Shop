@extends('layouts.storefront')

@section('content')
  <x-home.navbar />
  <div class="ec-side-cart-overlay"></div>
  <div id="ec-side-cart" class="ec-side-cart">
    <div class="ec-cart-inner">
      <div class="ec-cart-top">
        <div class="ec-cart-title">
          <span class="cart_title">My Cart</span>
          <button class="ec-close">×</button>
        </div>
        <livewire:home />
      </div>

    </div>
  </div>
  <!-- ekka Cart End -->

  <!-- Ec breadcrumb start -->
  <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="row ec_breadcrumb_inner">
            <div class="col-md-6 col-sm-12">
              <h2 class="ec-breadcrumb-title">Cart</h2>
            </div>
            <div class="col-md-6 col-sm-12">
              <!-- ec-breadcrumb-list start -->
              <ul class="ec-breadcrumb-list">
                <li class="ec-breadcrumb-item"><a href="{{ route("home") }}">Home</a></li>
                <li class="ec-breadcrumb-item active">Cart</li>
              </ul>
              <!-- ec-breadcrumb-list end -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Ec breadcrumb end -->

  <!-- Ec cart page -->
  <section class="ec-page-content section-space-p">
    <div class="container">
      <div class="row">
        <div class="ec-cart-leftside col-lg-8 col-md-12 ">
          <!-- cart content Start -->
          <div class="ec-cart-content">
            <div class="ec-cart-inner">
              <div class="row">
                <div class="table-content cart-table-content">
                  <table>
                    <thead>
                      <tr>
                        <th>{{ __("home.product") }}</th>
                        <th>{{ __("home.price") }}</th>
                        <th style="text-align: center;">{{ __("home.quantity") }}</th>
                        <th>{{ __("home.totalCart") }}</th>
                        <th>{{ __("home.offer") }}</th>
                      </tr>
                    </thead>

                    <tbody>
                      <livewire:items />
                    </tbody>
                  </table>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="ec-cart-update-bottom">
                      <a href="#">{{ __("home.shoping") }}</a>
                      <button class="btn btn-primary">{{__("home.checkout") }}</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--cart content End -->
        </div>
        <!-- Sidebar Area Start -->
        <livewire:receipt />
      </div>
    </div>
  </section>

  <!-- New Product Start -->
  <section class="section ec-new-product section-space-p">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <div class="section-title">
            <h2 class="ec-bg-title">{{ __("home.newArrival") }}</h2>
            <h2 class="ec-title">New Arrivals</h2>
            <p class="sub-title">Browse The Collection of Top Products</p>
          </div>
        </div>
      </div>
      <div class="row">
        <livewire:cards :products="$products" :products_offer="$products_offers" />
      </div>
    </div>
  </section>
  <!-- New Product end -->

  <!-- Footer Start -->
  <x-footer />
  <!-- Footer Area End -->

  <!-- Modal -->

  <!-- Modal end -->

  <!-- Footer navigation panel for responsive display -->
  <div class="ec-nav-toolbar">
    <div class="container">
      <div class="ec-nav-panel">
        <div class="ec-nav-panel-icons">
          <a href="#ec-mobile-menu" class="navbar-toggler-btn ec-header-btn ec-side-toggle"><i
              class="fi-rr-menu-burger"></i></a>
        </div>
        <div class="ec-nav-panel-icons">
          <a href="#ec-side-cart" class="toggle-cart ec-header-btn ec-side-toggle"><i
              class="fi-rr-shopping-bag"></i><span class="ec-cart-noti ec-header-count cart-count-lable">3</span></a>
        </div>
        <div class="ec-nav-panel-icons">
          <a href="index.html" class="ec-header-btn"><i class="fi-rr-home"></i></a>
        </div>
        <div class="ec-nav-panel-icons">
          <a href="wishlist.html" class="ec-header-btn"><i class="fi-rr-heart"></i><span
              class="ec-cart-noti">4</span></a>
        </div>
        <div class="ec-nav-panel-icons">
          <a href="login.html" class="ec-header-btn"><i class="fi-rr-user"></i></a>
        </div>

      </div>
    </div>
  </div>


  <x-category></x-category>

@endsection
