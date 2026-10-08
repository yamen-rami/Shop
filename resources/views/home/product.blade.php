@extends('layouts.storefront')

@section('content')
  @inject("offerService" , "App\Services\OfferService")
  {{-- <x-loader></x-loader> --}}
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
  </div>

  <!-- Ec breadcrumb start -->
  <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="row ec_breadcrumb_inner">
            <div class="col-md-6 col-sm-12">
              <h2 class="ec-breadcrumb-title">Product {{ $product->name }}</h2>
            </div>
            <div class="col-md-6 col-sm-12">
              <!-- ec-breadcrumb-list start -->
              <ul class="ec-breadcrumb-list">
                <li class="ec-breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="ec-breadcrumb-item active">Product</li>
                <li class="ec-breadcrumb-item active">{{ $product->name }}</li>
              </ul>
              <!-- ec-breadcrumb-list end -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Ec breadcrumb end -->

  <!-- Sart Single product -->
  <section class="ec-page-content section-space-p">
    <div class="container">
      <div class="row">
        <div class="ec-pro-rightside ec-common-rightside col-lg-9 order-lg-last col-md-12 order-md-first">

          <!-- Single product content Start -->
          <div class="single-pro-block">
            <div class="single-pro-inner">
              <div class="row">
                <div class="single-pro-img">
                  <x-product-gallery :product="$product" />
                </div>
                <div class="single-pro-desc">
                  <div class="single-pro-content">
                    <h5 class="ec-single-title"><strong>Name : </strong>{{ $product->name }}</h5>
                      <h5 class="ec-single-title"><strong>Price : </strong>${{ $offerService->getDiscount($product , $products_offers)["best"] }}</h5>
                      <h5 class="ec-single-title"><strong>Applied Offer : </strong>{{ $offerService->offerType($offerService->getDiscount($product , $products_offers)["offer"]) ?? __("home.noOffer") }}</h5>

                    <div class="ec-single-rating-wrap">
                    </div>
                    <div class="ec-single-desc"><strong>Description : </strong>{{ Str::limit($product->desc, 50)  }}
                    </div>
                    <div class="ec-single-qty d-flex " style='align-items: center; '>
                        {{-- <button class="btn btn-primary">Add To There</button> --}}
                        <livewire:add-to-cart :product="$product" />
                        <livewire:increment_decrement    :product="$product" :globalCart="$globalCart" />
                      </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--Single product content End -->
          <!-- Single product tab start -->
          <div class="ec-single-pro-tab">
            <div class="ec-single-pro-tab-wrapper">
              <div class="ec-single-pro-tab-nav">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#ec-spt-nav-details" role="tab"
                      aria-controls="ec-spt-nav-details" aria-selected="true">Detail</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#ec-spt-nav-info" role="tab"
                      aria-controls="ec-spt-nav-info" aria-selected="false">More Information</a>
                  </li>

                </ul>
              </div>
              <div class="tab-content  ec-single-pro-tab-content">
                <div id="ec-spt-nav-details" class="tab-pane fade show active">
                  <div class="ec-single-pro-tab-desc">
                    <p>
                    <p>Description : </p>
                    {{ $product->desc }}
                    </p>

                  </div>
                </div>
                <div id="ec-spt-nav-info" class="tab-pane fade">
                  <div class="ec-single-pro-tab-moreinfo">
                    <ul>
                      @forelse ($product->companies as $company)
                        <li><span>Company : </span> {{ $company->name }} </li>
                      @empty
                        <li><span>Company : </span> Anonymous </li>

                      @endforelse
                      @forelse ($product->tags as $tag)
                        <li><span>Tags : </span> {{ $tag->name }} </li>
                      @empty
                        <li><span>Tags : </span> There Is No Tags </li>

                      @endforelse
                    </ul>
                  </div>
                </div>

                <div id="ec-spt-nav-review" class="tab-pane fade">
                  <div class="row">
                    <div class="ec-t-review-wrapper">
                      <div class="ec-t-review-item">
                        <div class="ec-t-review-avtar">
                          <img src="assets/images/review-image/1.jpg" alt="" />
                        </div>
                        <div class="ec-t-review-content">
                          <div class="ec-t-review-top">
                            <div class="ec-t-review-name">Jeny Doe</div>
                            <div class="ec-t-review-rating">
                              <i class="ecicon eci-star fill"></i>
                              <i class="ecicon eci-star fill"></i>
                              <i class="ecicon eci-star fill"></i>
                              <i class="ecicon eci-star fill"></i>
                              <i class="ecicon eci-star-o"></i>
                            </div>
                          </div>
                          <div class="ec-t-review-bottom">
                            <p>Lorem Ipsum is simply dummy text of the printing and
                              typesetting industry. Lorem Ipsum has been the industry's
                              standard dummy text ever since the 1500s, when an unknown
                              printer took a galley of type and scrambled it to make a
                              type specimen.
                            </p>
                          </div>
                        </div>
                      </div>
                      <div class="ec-t-review-item">
                        <div class="ec-t-review-avtar">
                          <img src="assets/images/review-image/2.jpg" alt="" />
                        </div>
                        <div class="ec-t-review-content">
                          <div class="ec-t-review-top">
                            <div class="ec-t-review-name">Linda Morgus</div>
                            <div class="ec-t-review-rating">
                              <i class="ecicon eci-star fill"></i>
                              <i class="ecicon eci-star fill"></i>
                              <i class="ecicon eci-star fill"></i>
                              <i class="ecicon eci-star-o"></i>
                              <i class="ecicon eci-star-o"></i>
                            </div>
                          </div>
                          <div class="ec-t-review-bottom">
                            <p>Lorem Ipsum is simply dummy text of the printing and
                              typesetting industry. Lorem Ipsum has been the industry's
                              standard dummy text ever since the 1500s, when an unknown
                              printer took a galley of type and scrambled it to make a
                              type specimen.
                            </p>
                          </div>
                        </div>
                      </div>

                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- product details description area end -->
        </div>
        <!-- Sidebar Area Start -->
        <div class="ec-pro-leftside ec-common-leftside col-lg-3 order-lg-first col-md-12 order-md-last">
        </div>
        <!-- Sidebar Area Start -->
      </div>
    </div>
  </section>
  <!-- End Single product -->

  <!-- Related Product Start -->

  <!-- Related Product end -->

  <!-- Footer Start -->
  <x-footer />
  <!-- Footer Area End -->

  <!-- Modal -->
  <div class="modal fade" id="ec_quickview_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <button type="button" class="btn-close qty_close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-5 col-sm-12 col-xs-12">
              <!-- Swiper -->
              <div class="qty-product-cover">
                <div class="qty-slide">
                  <img class="img-responsive" src="assets/images/product-image/3_1.jpg" alt="">
                </div>
                <div class="qty-slide">
                  <img class="img-responsive" src="assets/images/product-image/3_2.jpg" alt="">
                </div>
                <div class="qty-slide">
                  <img class="img-responsive" src="assets/images/product-image/3_3.jpg" alt="">
                </div>
                <div class="qty-slide">
                  <img class="img-responsive" src="assets/images/product-image/3_4.jpg" alt="">
                </div>
                <div class="qty-slide">
                  <img class="img-responsive" src="assets/images/product-image/3_5.jpg" alt="">
                </div>
              </div>
              <div class="qty-nav-thumb">
                <div class="qty-slide">
                  <img class="img-responsive" src="assets/images/product-image/3_1.jpg" alt="">
                </div>
                <div class="qty-slide">
                  <img class="img-responsive" src="assets/images/product-image/3_2.jpg" alt="">
                </div>
                <div class="qty-slide">
                  <img class="img-responsive" src="assets/images/product-image/3_3.jpg" alt="">
                </div>
                <div class="qty-slide">
                  <img class="img-responsive" src="assets/images/product-image/3_4.jpg" alt="">
                </div>
                <div class="qty-slide">
                  <img class="img-responsive" src="assets/images/product-image/3_5.jpg" alt="">
                </div>
              </div>
            </div>
            <div class="col-md-7 col-sm-12 col-xs-12">
              <div class="quickview-pro-content">
                <h5 class="ec-quick-title"><a href="product-left-sidebar.html">Handbag leather purse for women</a>
                </h5>
                <div class="ec-quickview-rating">
                  <i class="ecicon eci-star fill"></i>
                  <i class="ecicon eci-star fill"></i>
                  <i class="ecicon eci-star fill"></i>
                  <i class="ecicon eci-star fill"></i>
                  <i class="ecicon eci-star"></i>
                </div>

                <div class="ec-quickview-desc">Lorem Ipsum is simply dummy text of the printing and
                  typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever
                  since the 1500s,</div>
                <div class="ec-quickview-price">
                  <span class="old-price">$100.00</span>
                  <span class="new-price">$80.00</span>
                </div>

                <div class="ec-pro-variation">
                  <div class="ec-pro-variation-inner ec-pro-variation-color">
                    <span>Color</span>
                    <div class="ec-pro-color">
                      <ul class="ec-opt-swatch">
                        <li><span style="background-color:#696d62;"></span></li>
                        <li><span style="background-color:#d73808;"></span></li>
                        <li><span style="background-color:#577023;"></span></li>
                        <li><span style="background-color:#2ea1cd;"></span></li>
                      </ul>
                    </div>
                  </div>
                  <div class="ec-pro-variation-inner ec-pro-variation-size ec-pro-size">
                    <span>Size</span>
                    <div class="ec-pro-variation-content">
                      <ul class="ec-opt-size">
                        <li class="active"><a href="#" class="ec-opt-sz" data-tooltip="Small">S</a></li>
                        <li><a href="#" class="ec-opt-sz" data-tooltip="Medium">M</a></li>
                        <li><a href="#" class="ec-opt-sz" data-tooltip="Large">X</a></li>
                        <li><a href="#" class="ec-opt-sz" data-tooltip="Extra Large">XL</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="ec-quickview-qty">
                  <div class="qty-plus-minus">
                    <input class="qty-input" type="text" name="ec_qtybtn" value="1" />
                  </div>
                  <div class="ec-quickview-cart ">
                    <button class="btn btn-primary"><i class="fi-rr-heart"></i> Add To There</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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


  <x-category />
@endsection
