@extends('layouts.storefront')

@section('content')
  <x-loader>
  </x-loader>
  <x-home.navbar />
  <div class="sticky-header-next-sec ec-main-slider section section-space-pb">
    <div class="ec-slider swiper-container main-slider-nav main-slider-dot">
      <!-- Main slider -->
      <div class="swiper-wrapper">
        @foreach($slider as $s)
          <div class="ec-slide-item  swiper-slide d-flex ec-slide-1"
            style="background: url('{{ \App\Support\ImageUrl::resolve($s->image?->path) }}') center/cover no-repeat, url('{{ \App\Support\ImageUrl::placeholder() }}') center/cover no-repeat; min-height: 50vh;">
            <div class="container align-self-center">
              <div class="row">
                <div class="col-xl-6 col-lg-7 col-md-7 col-sm-7 align-self-center">
                  <div class="ec-slide-content slider-animation">
                    <h1 class="ec-slide-title">
                      <a class="text-light" href="{{ route("showProduct", $s->id) }}">
                        {{ $s->name }}
                      </a>
                    </h1>
                    <h2 class="ec-slide-stitle  text-light  ">$
                      @if($s->has_discount)
                        <s class="text-light">{{ $s->price }}</s>
                        <span class="text-light">{{ $s->discount_price }}</span>
                      @else
                        <span class="text-light">{{ $s->price }}</span>
                      @endif


                    </h2>
                    <p class=" text-light  ">{{ Str::limit($s->desc, 60) }}</p>
                    <livewire:add-to-cart :product="$s" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach

      </div>
      <div class="swiper-pagination swiper-pagination-white"></div>
      <div class="swiper-buttons">
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>
    </div>
  </div>
  <div>
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

    <!-- Category Sidebar start -->
    <x-category />


    <section class="section ec-product-tab section-space-p" id="collection">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <div class="section-title">
              <h2 class="ec-bg-title">{{ __("home.collection") }}</h2>
              <p class="sub-title">{{ __("home.browse") }}</p>
            </div>
          </div>

          <!-- Tab Start -->
          <div class="col-md-12 text-center">
            <ul class="ec-pro-tab-nav nav justify-content-center">
              <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-pro-for-men">{{ __("home.ourProducts") }}</a>
              </li>
            </ul>
            <div class="row">
              <div class="col">
                <div class="tab-content">
                  <!-- 1st Product tab start -->
                  <div class="tab-pane fade show active" id="tab-pro-for-all">
                    <div class="row">
                      <livewire:cards :products="$products->getCollection()" :products_offer="$offers" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tab End -->
        </div>

      </div>
    </section>
    <!-- ec Product tab Area End -->

    



    <!--  services Section Start -->
    <section class="section ec-services-section section-space-p" id="services">
      <h2 class="d-none">Services</h2>
      <div class="container">
        <div class="row">
          <div class="ec_ser_content ec_ser_content_1 col-sm-12 col-md-6 col-lg-3" data-animation="zoomIn">
            <div class="ec_ser_inner">
              <div class="ec-service-image">
                <i class="fi fi-ts-truck-moving"></i>
              </div>
              <div class="ec-service-desc">
                <h2>Free Shipping</h2>
                <p>Free shipping on all US order or order above $200</p>
              </div>
            </div>
          </div>
          <div class="ec_ser_content ec_ser_content_2 col-sm-12 col-md-6 col-lg-3" data-animation="zoomIn">
            <div class="ec_ser_inner">
              <div class="ec-service-image">
                <i class="fi fi-ts-hand-holding-seeding"></i>
              </div>
              <div class="ec-service-desc">
                <h2>24X7 Support</h2>
                <p>Contact us 24 hours a day, 7 days a week</p>
              </div>
            </div>
          </div>
          <div class="ec_ser_content ec_ser_content_3 col-sm-12 col-md-6 col-lg-3" data-animation="zoomIn">
            <div class="ec_ser_inner">
              <div class="ec-service-image">
                <i class="fi fi-ts-badge-percent"></i>
              </div>
              <div class="ec-service-desc">
                <h2>30 Days Return</h2>
                <p>Simply return it within 30 days for an exchange</p>
              </div>
            </div>
          </div>
          <div class="ec_ser_content ec_ser_content_4 col-sm-12 col-md-6 col-lg-3" data-animation="zoomIn">
            <div class="ec_ser_inner">
              <div class="ec-service-image">
                <i class="fi fi-ts-donate"></i>
              </div>
              <div class="ec-service-desc">
                <h2>Payment Secure</h2>
                <p>Contact us 24 hours a day, 7 days a week</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--services Section End -->

    <!-- offer Section End -->

 
    <!-- New Product end -->


    <!-- ec testmonial end -->

    <!-- Ec Brand Section Start -->

    <!-- Ec Brand Section End -->

    <!-- Ec Instagram Start -->


    <x-footer />

    {{--
    <livewire:show /> --}}

    <x-home.menu />
    <livewire:show />
    <x-cart />


@endsection
