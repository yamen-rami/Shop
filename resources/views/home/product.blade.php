<x-app>
  <x-loader></x-loader>
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
                  <img class="img-responsive" src="{{ asset($product->image) }}" alt="">
                </div>
                <div class="single-pro-desc">
                  <div class="single-pro-content">
                    <h5 class="ec-single-title"><strong>Name : </strong>{{ $product->name }}</h5>
                    @if($product->discount_price == $product->price)
                      <h5 class="ec-single-title"><strong>Price : </strong>{{ $product->discount_price }}</h5>
                    @else
                      <h5 class="ec-single-title">{{ $product->discount_price }}</h5>
                      <s class="ec-single-title">{{ $product->price }}</s>


                    @endif

                    <div class="ec-single-rating-wrap">
                    </div>
                    <div class="ec-single-desc"><strong>Description : </strong>{{ Str::limit($product->desc, 50)  }}
                    </div>
                    <div class="ec-single-qty">

                      <div class="ec-single-cart ">
                        {{-- <button class="btn btn-primary">Add To There</button> --}}
                        <livewire:add-to-cart :product="$product" />
                      </div>
                      <livewire:increment_decrement :product="$product" />

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
  <section class="section ec-releted-product section-space-p">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <div class="section-title">
            <h2 class="ec-bg-title">Related products</h2>
            <h2 class="ec-title">Related products</h2>
            <p class="sub-title">Browse The Collection of Top Products</p>
          </div>
        </div>
      </div>
      <div class="row margin-minus-b-30">
        <!-- Related Product Content -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-6 pro-gl-content">
          <div class="ec-product-inner">
            <div class="ec-pro-image-outer">
              <div class="ec-pro-image">
                <a href="product-left-sidebar.html" class="image">
                  <img class="main-image" src="assets/images/product-image/6_1.jpg" alt="Product" />
                  <img class="hover-image" src="assets/images/product-image/6_2.jpg" alt="Product" />
                </a>
                <span class="percentage">20%</span>
                <a href="#" class="quickview" data-link-action="quickview" title="Quick view" data-bs-toggle="modal"
                  data-bs-target="#ec_quickview_modal"><i class="fi-rr-eye"></i></a>
                <div class="ec-pro-actions">
                  <a href="compare.html" class="ec-btn-group compare" title="Compare"><i
                      class="fi fi-rr-arrows-repeat"></i></a>
                  <a class="ec-btn-group wishlist" title="Wishlist"><i class="fi-rr-heart"></i></a>
                </div>
              </div>
            </div>
            <div class="ec-pro-content">
              <h5 class="ec-pro-title"><a href="product-left-sidebar.html">Round Neck T-Shirt</a></h5>
              <div class="ec-pro-rating">
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star"></i>
              </div>
              <div class="ec-pro-list-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                Lorem Ipsum is simply dutmmy text ever since the 1500s, when an unknown printer took a galley.</div>
              <span class="ec-price">
                <span class="old-price">$27.00</span>
                <span class="new-price">$22.00</span>
              </span>
              <div class="ec-pro-option">
                <div class="ec-pro-color">
                  <span class="ec-pro-opt-label">Color</span>
                  <ul class="ec-opt-swatch ec-change-img">
                    <li class="active"><a href="#" class="ec-opt-clr-img" data-src="assets/images/product-image/6_1.jpg"
                        data-src-hover="assets/images/product-image/6_1.jpg" data-tooltip="Gray"><span
                          style="background-color:#e8c2ff;"></span></a></li>
                    <li><a href="#" class="ec-opt-clr-img" data-src="assets/images/product-image/6_2.jpg"
                        data-src-hover="assets/images/product-image/6_2.jpg" data-tooltip="Orange"><span
                          style="background-color:#9cfdd5;"></span></a></li>
                  </ul>
                </div>
                <div class="ec-pro-size">
                  <span class="ec-pro-opt-label">Size</span>
                  <ul class="ec-opt-size">
                    <li class="active"><a href="#" class="ec-opt-sz" data-old="$25.00" data-new="$20.00"
                        data-tooltip="Small">S</a></li>
                    <li><a href="#" class="ec-opt-sz" data-old="$27.00" data-new="$22.00" data-tooltip="Medium">M</a>
                    </li>
                    <li><a href="#" class="ec-opt-sz" data-old="$35.00" data-new="$30.00"
                        data-tooltip="Extra Large">XL</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-6 pro-gl-content">
          <div class="ec-product-inner">
            <div class="ec-pro-image-outer">
              <div class="ec-pro-image">
                <a href="product-left-sidebar.html" class="image">
                  <img class="main-image" src="assets/images/product-image/7_1.jpg" alt="Product" />
                  <img class="hover-image" src="assets/images/product-image/7_2.jpg" alt="Product" />
                </a>
                <span class="percentage">20%</span>
                <span class="flags">
                  <span class="sale">Sale</span>
                </span>
                <a href="#" class="quickview" data-link-action="quickview" title="Quick view" data-bs-toggle="modal"
                  data-bs-target="#ec_quickview_modal"><i class="fi-rr-eye"></i></a>
                <div class="ec-pro-actions">
                  <a href="compare.html" class="ec-btn-group compare" title="Compare"><i
                      class="fi fi-rr-arrows-repeat"></i></a>
                  <button title="Add To Cart" class="add-to-cart"><i class="fi-rr-shopping-basket"></i> Add To
                    Cart</button>
                  <a class="ec-btn-group wishlist" title="Wishlist"><i class="fi-rr-heart"></i></a>
                </div>
              </div>
            </div>
            <div class="ec-pro-content">
              <h5 class="ec-pro-title"><a href="product-left-sidebar.html">Full Sleeve Shirt</a></h5>
              <div class="ec-pro-rating">
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star"></i>
              </div>
              <div class="ec-pro-list-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                Lorem Ipsum is simply dutmmy text ever since the 1500s, when an unknown printer took a galley.</div>
              <span class="ec-price">
                <span class="old-price">$12.00</span>
                <span class="new-price">$10.00</span>
              </span>
              <div class="ec-pro-option">
                <div class="ec-pro-color">
                  <span class="ec-pro-opt-label">Color</span>
                  <ul class="ec-opt-swatch ec-change-img">
                    <li class="active"><a href="#" class="ec-opt-clr-img" data-src="assets/images/product-image/7_1.jpg"
                        data-src-hover="assets/images/product-image/7_1.jpg" data-tooltip="Gray"><span
                          style="background-color:#01f1f1;"></span></a></li>
                    <li><a href="#" class="ec-opt-clr-img" data-src="assets/images/product-image/7_2.jpg"
                        data-src-hover="assets/images/product-image/7_2.jpg" data-tooltip="Orange"><span
                          style="background-color:#b89df8;"></span></a></li>
                  </ul>
                </div>
                <div class="ec-pro-size">
                  <span class="ec-pro-opt-label">Size</span>
                  <ul class="ec-opt-size">
                    <li class="active"><a href="#" class="ec-opt-sz" data-old="$12.00" data-new="$10.00"
                        data-tooltip="Small">S</a></li>
                    <li><a href="#" class="ec-opt-sz" data-old="$15.00" data-new="$12.00" data-tooltip="Medium">M</a>
                    </li>
                    <li><a href="#" class="ec-opt-sz" data-old="$20.00" data-new="$17.00"
                        data-tooltip="Extra Large">XL</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-6 pro-gl-content">
          <div class="ec-product-inner">
            <div class="ec-pro-image-outer">
              <div class="ec-pro-image">
                <a href="product-left-sidebar.html" class="image">
                  <img class="main-image" src="assets/images/product-image/1_1.jpg" alt="Product" />
                  <img class="hover-image" src="assets/images/product-image/1_2.jpg" alt="Product" />
                </a>
                <span class="percentage">20%</span>
                <span class="flags">
                  <span class="sale">Sale</span>
                </span>
                <a href="#" class="quickview" data-link-action="quickview" title="Quick view" data-bs-toggle="modal"
                  data-bs-target="#ec_quickview_modal"><i class="fi-rr-eye"></i></a>
                <div class="ec-pro-actions">
                  <a href="compare.html" class="ec-btn-group compare" title="Compare"><i
                      class="fi fi-rr-arrows-repeat"></i></a>
                  <button title="Add To Cart" class="add-to-cart"><i class="fi-rr-shopping-basket"></i> Add To
                    Cart</button>
                  <a class="ec-btn-group wishlist" title="Wishlist"><i class="fi-rr-heart"></i></a>
                </div>
              </div>
            </div>
            <div class="ec-pro-content">
              <h5 class="ec-pro-title"><a href="product-left-sidebar.html">Cute Baby Toy's</a></h5>
              <div class="ec-pro-rating">
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star"></i>
              </div>
              <div class="ec-pro-list-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                Lorem Ipsum is simply dutmmy text ever since the 1500s, when an unknown printer took a galley.</div>
              <span class="ec-price">
                <span class="old-price">$40.00</span>
                <span class="new-price">$30.00</span>
              </span>
              <div class="ec-pro-option">
                <div class="ec-pro-color">
                  <span class="ec-pro-opt-label">Color</span>
                  <ul class="ec-opt-swatch ec-change-img">
                    <li class="active"><a href="#" class="ec-opt-clr-img" data-src="assets/images/product-image/1_1.jpg"
                        data-src-hover="assets/images/product-image/1_1.jpg" data-tooltip="Gray"><span
                          style="background-color:#90cdf7;"></span></a></li>
                    <li><a href="#" class="ec-opt-clr-img" data-src="assets/images/product-image/1_2.jpg"
                        data-src-hover="assets/images/product-image/1_2.jpg" data-tooltip="Orange"><span
                          style="background-color:#ff3b66;"></span></a></li>
                    <li><a href="#" class="ec-opt-clr-img" data-src="assets/images/product-image/1_3.jpg"
                        data-src-hover="assets/images/product-image/1_3.jpg" data-tooltip="Green"><span
                          style="background-color:#ffc476;"></span></a></li>
                    <li><a href="#" class="ec-opt-clr-img" data-src="assets/images/product-image/1_4.jpg"
                        data-src-hover="assets/images/product-image/1_4.jpg" data-tooltip="Sky Blue"><span
                          style="background-color:#1af0ba;"></span></a></li>
                  </ul>
                </div>
                <div class="ec-pro-size">
                  <span class="ec-pro-opt-label">Size</span>
                  <ul class="ec-opt-size">
                    <li class="active"><a href="#" class="ec-opt-sz" data-old="$40.00" data-new="$30.00"
                        data-tooltip="Small">S</a></li>
                    <li><a href="#" class="ec-opt-sz" data-old="$50.00" data-new="$40.00" data-tooltip="Medium">M</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-6 pro-gl-content">
          <div class="ec-product-inner">
            <div class="ec-pro-image-outer">
              <div class="ec-pro-image">
                <a href="product-left-sidebar.html" class="image">
                  <img class="main-image" src="assets/images/product-image/2_1.jpg" alt="Product" />
                  <img class="hover-image" src="assets/images/product-image/2_2.jpg" alt="Product" />
                </a>
                <span class="percentage">20%</span>
                <span class="flags">
                  <span class="new">New</span>
                </span>
                <a href="#" class="quickview" data-link-action="quickview" title="Quick view" data-bs-toggle="modal"
                  data-bs-target="#ec_quickview_modal"><i class="fi-rr-eye"></i></a>
                <div class="ec-pro-actions">
                  <a href="compare.html" class="ec-btn-group compare" title="Compare"><i
                      class="fi fi-rr-arrows-repeat"></i></a>
                  <button title="Add To Cart" class="add-to-cart"><i class="fi-rr-shopping-basket"></i> Add To
                    Cart</button>
                  <a class="ec-btn-group wishlist" title="Wishlist"><i class="fi-rr-heart"></i></a>
                </div>
              </div>
            </div>
            <div class="ec-pro-content">
              <h5 class="ec-pro-title"><a href="product-left-sidebar.html">Jumbo Carry Bag</a></h5>
              <div class="ec-pro-rating">
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star fill"></i>
                <i class="ecicon eci-star"></i>
              </div>
              <div class="ec-pro-list-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                Lorem Ipsum is simply dutmmy text ever since the 1500s, when an unknown printer took a galley.</div>
              <span class="ec-price">
                <span class="old-price">$50.00</span>
                <span class="new-price">$40.00</span>
              </span>
              <div class="ec-pro-option">
                <div class="ec-pro-color">
                  <span class="ec-pro-opt-label">Color</span>
                  <ul class="ec-opt-swatch ec-change-img">
                    <li class="active"><a href="#" class="ec-opt-clr-img" data-src="assets/images/product-image/2_1.jpg"
                        data-src-hover="assets/images/product-image/2_2.jpg" data-tooltip="Gray"><span
                          style="background-color:#fdbf04;"></span></a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Related Product end -->

  <!-- Footer Start -->
  <footer class="ec-footer section-space-mt">
    <div class="footer-container">
      <div class="footer-offer">
        <div class="container">
          <div class="row">
            <div class="text-center footer-off-msg">
              <span>Win a contest! Get this limited-editon</span><a href="#" target="_blank">View
                Detail</a>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-top section-space-footer-p">
        <div class="container">
          <div class="row">
            <div class="col-sm-12 col-lg-3 ec-footer-contact">
              <div class="ec-footer-widget">
                <div class="ec-footer-logo"><a href="#"><img src="assets/images/logo/footer-logo.png" alt=""><img
                      class="dark-footer-logo" src="assets/images/logo/dark-logo.png" alt="Site Logo"
                      style="display: none;" /></a></div>
                <h4 class="ec-footer-heading">Contact us</h4>
                <div class="ec-footer-links">
                  <ul class="align-items-center">
                    <li class="ec-footer-link">71 Pilgrim Avenue Chevy Chase, east california.</li>
                    <li class="ec-footer-link"><span>Call Us:</span><a href="tel:+440123456789">+44
                        0123 456 789</a></li>
                    <li class="ec-footer-link"><span>Email:</span><a
                        href="mailto:example@ec-email.com">+example@ec-email.com</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-lg-2 ec-footer-info">
              <div class="ec-footer-widget">
                <h4 class="ec-footer-heading">Information</h4>
                <div class="ec-footer-links">
                  <ul class="align-items-center">
                    <li class="ec-footer-link"><a href="about-us.html">About us</a></li>
                    <li class="ec-footer-link"><a href="faq.html">FAQ</a></li>
                    <li class="ec-footer-link"><a href="#">Delivery Information</a></li>
                    <li class="ec-footer-link"><a href="contact-us.html">Contact us</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-lg-2 ec-footer-account">
              <div class="ec-footer-widget">
                <h4 class="ec-footer-heading">Account</h4>
                <div class="ec-footer-links">
                  <ul class="align-items-center">
                    <li class="ec-footer-link"><a href="#">My Account</a></li>
                    <li class="ec-footer-link"><a href="track-order.html">Order History</a></li>
                    <li class="ec-footer-link"><a href="#">Wish List</a></li>
                    <li class="ec-footer-link"><a href="#">Specials</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-lg-2 ec-footer-service">
              <div class="ec-footer-widget">
                <h4 class="ec-footer-heading">Services</h4>
                <div class="ec-footer-links">
                  <ul class="align-items-center">
                    <li class="ec-footer-link"><a href="#">Discount Returns</a></li>
                    <li class="ec-footer-link"><a href="#">Policy & policy </a></li>
                    <li class="ec-footer-link"><a href="#">Customer Service</a></li>
                    <li class="ec-footer-link"><a href="terms-condition.html">Term & condition</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-lg-3 ec-footer-news">
              <div class="ec-footer-widget">
                <h4 class="ec-footer-heading">Newsletter</h4>
                <div class="ec-footer-links">
                  <ul class="align-items-center">
                    <li class="ec-footer-link">Get instant updates about our new products and
                      special promos!</li>
                  </ul>
                  <div class="ec-subscribe-form">
                    <form id="ec-newsletter-form" name="ec-newsletter-form" method="post" action="#">
                      <div id="ec_news_signup" class="ec-form">
                        <input class="ec-email" type="email" required="" placeholder="Enter your email here..."
                          name="ec-email" value="" />
                        <button id="ec-news-btn" class="button btn-primary" type="submit" name="subscribe" value=""><i
                            class="ecicon eci-paper-plane-o" aria-hidden="true"></i></button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="container">
          <div class="row align-items-center">
            <!-- Footer social Start -->
            <div class="col text-left footer-bottom-left">
              <div class="footer-bottom-social">
                <span class="social-text text-upper">Follow us on:</span>
                <ul class="mb-0">
                  <li class="list-inline-item"><a class="hdr-facebook" href="#"><i class="ecicon eci-facebook"></i></a>
                  </li>
                  <li class="list-inline-item"><a class="hdr-twitter" href="#"><i class="ecicon eci-twitter"></i></a>
                  </li>
                  <li class="list-inline-item"><a class="hdr-instagram" href="#"><i
                        class="ecicon eci-instagram"></i></a></li>
                  <li class="list-inline-item"><a class="hdr-linkedin" href="#"><i class="ecicon eci-linkedin"></i></a>
                  </li>
                </ul>
              </div>
            </div>
            <!-- Footer social End -->
            <!-- Footer Copyright Start -->
            <div class="col text-center footer-copy">
              <div class="footer-bottom-copy ">
                <div class="ec-copy">Copyright © <span id="copyright_year"></span> <a class="site-name text-upper"
                    href="#">ekka<span>.</span></a>. All Rights Reserved</div>
              </div>
            </div>
            <!-- Footer Copyright End -->
            <!-- Footer payment -->
            <div class="col footer-bottom-right">
              <div class="footer-bottom-payment d-flex justify-content-end">
                <div class="payment-link">
                  <img src="assets/images/icons/payment.png" alt="">
                </div>

              </div>
            </div>
            <!-- Footer payment -->
          </div>
        </div>
      </div>
    </div>
  </footer>
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
  <!-- Footer navigation panel for responsive display end -->

  <!-- Recent Purchase Popup  -->
  <div class="recent-purchase">
    <img src="assets/images/product-image/1.jpg" alt="payment image">
    <div class="detail">
      <p>Someone in new just bought</p>
      <h6>stylish baby shoes</h6>
      <p>10 Minutes ago</p>
    </div>
    <a href="javascript:void(0)" class="icon-btn recent-close">×</a>
  </div>
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

  <!-- Whatsapp -->
  <div class="ec-style ec-right-bottom">
    <!-- Start Floating Panel Container -->
    <div class="ec-panel">
      <!-- Panel Header -->
      <div class="ec-header">
        <strong>Need Help?</strong>
        <p>Chat with us on WhatsApp</p>
      </div>
      <!-- Panel Content -->
      <div class="ec-body">
        <ul>
          <!-- Start Single Contact List -->
          <li>
            <a class="ec-list" data-number="918866774266"
              data-message="Please help me! I have got wrong product - ORDER ID is : #654321485">
              <div class="d-flex bd-highlight">
                <!-- Profile Picture -->
                <div class="ec-img-cont">
                  <img src="assets/images/whatsapp/profile_01.jpg" class="ec-user-img" alt="Profile image">
                  <span class="ec-status-icon"></span>
                </div>
                <!-- Display Name & Last Seen -->
                <div class="ec-user-info">
                  <span>Sahar Darya</span>
                  <p>Sahar left 7 mins ago</p>
                </div>
                <!-- Chat iCon -->
                <div class="ec-chat-icon">
                  <i class="fa fa-whatsapp"></i>
                </div>
              </div>
            </a>
          </li>
          <!--/ End Single Contact List -->
          <!-- Start Single Contact List -->
          <li>
            <a class="ec-list" data-number="918866774266"
              data-message="Please help me! I have got wrong product - ORDER ID is : #654321485">
              <div class="d-flex bd-highlight">
                <!-- Profile Picture -->
                <div class="ec-img-cont">
                  <img src="assets/images/whatsapp/profile_02.jpg" class="ec-user-img" alt="Profile image">
                  <span class="ec-status-icon ec-online"></span>
                </div>
                <!-- Display Name & Last Seen -->
                <div class="ec-user-info">
                  <span>Yolduz Rafi</span>
                  <p>Yolduz is online</p>
                </div>
                <!-- Chat iCon -->
                <div class="ec-chat-icon">
                  <i class="fa fa-whatsapp"></i>
                </div>
              </div>
            </a>
          </li>
          <!--/ End Single Contact List -->
          <!-- Start Single Contact List -->
          <li>
            <a class="ec-list" data-number="918866774266"
              data-message="Please help me! I have got wrong product - ORDER ID is : #654321485">
              <div class="d-flex bd-highlight">
                <!-- Profile Picture -->
                <div class="ec-img-cont">
                  <img src="assets/images/whatsapp/profile_03.jpg" class="ec-user-img" alt="Profile image">
                  <span class="ec-status-icon ec-offline"></span>
                </div>
                <!-- Display Name & Last Seen -->
                <div class="ec-user-info">
                  <span>Nargis Hawa</span>
                  <p>Nargis left 30 mins ago</p>
                </div>
                <!-- Chat iCon -->
                <div class="ec-chat-icon">
                  <i class="fa fa-whatsapp"></i>
                </div>
              </div>
            </a>
          </li>
          <!--/ End Single Contact List -->
          <!-- Start Single Contact List -->
          <li>
            <a class="ec-list" data-number="918866774266"
              data-message="Please help me! I have got wrong product - ORDER ID is : #654321485">
              <div class="d-flex bd-highlight">
                <!-- Profile Picture -->
                <div class="ec-img-cont">
                  <img src="assets/images/whatsapp/profile_04.jpg" class="ec-user-img" alt="Profile image">
                  <span class="ec-status-icon ec-offline"></span>
                </div>
                <!-- Display Name & Last Seen -->
                <div class="ec-user-info">
                  <span>Khadija Mehr</span>
                  <p>Khadija left 50 mins ago</p>
                </div>
                <!-- Chat iCon -->
                <div class="ec-chat-icon">
                  <i class="fa fa-whatsapp"></i>
                </div>
              </div>
            </a>
          </li>
          <!--/ End Single Contact List -->
        </ul>
      </div>
    </div>
    <!--/ End Floating Panel Container -->
    <!-- Start Right Floating Button-->
    <div class="ec-right-bottom">
      <div class="ec-box">
        <div class="ec-button rotateBackward">
          <img class="whatsapp" src="assets/images/common/whatsapp.png" alt="whatsapp icon" />
        </div>
      </div>
    </div>
    <!--/ End Right Floating Button-->
  </div>
  <x-category />
</x-app>