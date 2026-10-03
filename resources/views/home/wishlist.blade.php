@extends('layouts.storefront')

@section('title')
Wishlist
@endsection

@section('content')


  <x-loader>

  </x-loader>

  <x-home.navbar></x-home.navbar>

  <!-- ekka Cart Start -->
  <div class="ec-side-cart-overlay"></div>
  <div id="ec-side-cart" class="ec-side-cart">
    <div class="ec-cart-inner">
      <div class="ec-cart-top">
        <div class="ec-cart-title">
          <span class="cart_title">My Cart</span>
          <button class="ec-close">×</button>
        </div>
        <livewire:home>

        </livewire:home>
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
              <h2 class="ec-breadcrumb-title">Wishlist</h2>
            </div>
            <div class="col-md-6 col-sm-12">
              <!-- ec-breadcrumb-list start -->
              <ul class="ec-breadcrumb-list">
                <li class="ec-breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="ec-breadcrumb-item active">Wishlist</li>
              </ul>
              <!-- ec-breadcrumb-list end -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Ec breadcrumb end -->

  <!-- Ec Wishlist page -->
  <section class="ec-page-content section-space-p">
    <div class="container">
      <div class="row">
        <!-- Compare Content Start -->
        <div class="ec-wish-rightside col-lg-12 col-md-12">
          <!-- Compare content Start -->
          <div class="ec-compare-content">
            <div class="ec-compare-inner">
              <div class="row margin-minus-b-30">
                @foreach($favoraites as $d)
                  <livewire:wishlist_cards :product="$d->product" :products_offer="$products_offers" />
                @endforeach
                {{ $favoraites->links() }}
              </div>
            </div>
          </div>
          <!--compare content End -->
        </div>
        <!-- Compare Content end -->
      </div>
    </div>
  </section>

  <!-- Footer Start -->

  <!-- Footer Area End -->

  <!-- Recent Purchase Popup end -->

  <!-- Cart Floating Button -->
  <div class="ec-cart-float">
    <a href="#ec-side-cart" class="ec-header-btn ec-side-toggle">
      <div class="header-icon"><i class="fi-rr-shopping-basket"></i>
      </div>
      <span class="ec-cart-count cart-count-lable">3</span>
    </a>
  </div>
  <!-- Cart Floating Button end -->

  <livewire:show />

  <x-category></x-category>
@endsection
