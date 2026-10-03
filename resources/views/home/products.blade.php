<x-app>
  <x-slot:title>
    {{ __("home.products") }}
  </x-slot:title>
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
              <h2 class="ec-breadcrumb-title">Products</h2>
            </div>
            <div class="col-md-6 col-sm-12">
              <!-- ec-breadcrumb-list start -->
              <ul class="ec-breadcrumb-list">
                <li class="ec-breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="ec-breadcrumb-item active">Products</li>
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
            <h2 class="ec-bg-title">Products</h2>
            <h2 class="ec-title">{{ __("home.ourProducts") }}</h2>
            <p class="sub-title">{{ __("home.browse") }}</p>
          </div>
        </div>
        <div class="d-grid justify-content-center col-lg-12">
          <form action="{{ route("products") }}">

            <div class="header-search d-flex mb-5">

              <input type="text" class="form-control ec-search-bar border-none" placeholder="{{ __("home.search") }}"
                name="search">
              <button class="text-light bg-primary">{{ __("home.buttonSearch") }}</button>
            </div>
          </form>

        </div>
        @foreach($products as $product)
          <livewire:cards :product="$product" :products_offer="$offers" wire:key='$product->id' />
        @endforeach
      </div>
      {{ $products->links() }}
    </div>
  </section>



  <!-- Footer Start -->

  <livewire:show />
  <!-- Footer Area End -->
  <x-category />
  <x-cart></x-cart>

  <x-footer></x-footer>
  <x-home.menu></x-home.menu>
</x-app>