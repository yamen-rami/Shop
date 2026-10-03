@extends('layouts.storefront')

@section('title')
{{ __("home.offers") }}
@endsection

@section('content')

  <x-loader>

  </x-loader>
  <x-home.navbar>

  </x-home.navbar>

  <!-- ekka Cart Start -->
  <div class="ec-side-cart-overlay"></div>
  <div id="ec-side-cart" class="ec-side-cart">
    <div class="ec-cart-inner">
      <div class="ec-cart-top">
        <div class="ec-cart-title">
          <span class="cart_title">{{ __("home.myCart") }}</span>
          <button class="ec-close">×</button>
        </div>
        <livewire:home></livewire:home>
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
              <h2 class="ec-breadcrumb-title">Current Offers</h2>
            </div>
            <div class="col-md-6 col-sm-12">
              <!-- ec-breadcrumb-list start -->
              <ul class="ec-breadcrumb-list">
                <li class="ec-breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="ec-breadcrumb-item active">Current Offers</li>
              </ul>
              <!-- ec-breadcrumb-list end -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="sec-csc el-prod section-space-p">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <div class="section-title">
            <h2 class="ec-bg-title">Current offers</h2>
          </div>
        </div>
        <livewire:public-offers />
      </div>
    </div>
  </section>



  <!-- Footer Start -->

  <livewire:show />
  <!-- Footer Area End -->
  <x-category />


  <x-footer></x-footer>

@endsection
