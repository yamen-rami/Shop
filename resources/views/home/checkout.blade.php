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
                <li class="ec-breadcrumb-item"><a href="index.html">Home</a></li>
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
                      <livewire:items :globalCart="$globalCart" :offers="$products_offers" />
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
        <livewire:receipt :globalCart="$globalCart" :globalOffer="$products_offers" />
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
                <h5 class="ec-quick-title"><a href="product-left-sidebar.html">Handbag leather purse for
                    women</a>
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
                    <button class="btn btn-primary"><i class="fi-rr-shopping-basket"></i> Add To
                      Cart</button>
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
  <!-- Whatsapp end -->

  <!-- Feature tools -->
  <div class="ec-tools-sidebar-overlay"></div>
  <div class="ec-tools-sidebar">
    <div class="tool-title">
      <h3>Features</h3>
    </div>
    <a href="#" class="ec-tools-sidebar-toggle in-out">
      <img alt="icon" src="assets/images/common/settings.png" />
    </a>
    <div class="ec-tools-detail">
      <div class="ec-tools-sidebar-content ec-change-color ec-color-desc">
        <h3>Color Scheme</h3>
        <ul class="bg-panel">
          <li class="active" data-color="01"><a href="#" class="colorcode1"></a></li>
          <li data-color="02"><a href="#" class="colorcode2"></a></li>
          <li data-color="03"><a href="#" class="colorcode3"></a></li>
          <li data-color="04"><a href="#" class="colorcode4"></a></li>
          <li data-color="05"><a href="#" class="colorcode5"></a></li>
        </ul>
      </div>
      <div class="ec-tools-sidebar-content">
        <h3>Backgrounds</h3>
        <ul class="bg-panel">
          <li class="bg"><a class="back-bg-1" id="bg-1">Background-1</a></li>
          <li class="bg"><a class="back-bg-2" id="bg-2">Background-2</a></li>
          <li class="bg"><a class="back-bg-3" id="bg-3">Background-3</a></li>
          <li class="bg"><a class="back-bg-4" id="bg-4">Default</a></li>
        </ul>
      </div>
      <div class="ec-tools-sidebar-content">
        <h3>Full Screen mode</h3>
        <div class="ec-fullscreen-mode">
          <div class="ec-fullscreen-switch">
            <div class="ec-fullscreen-btn">Mode</div>
            <div class="ec-fullscreen-on">On</div>
            <div class="ec-fullscreen-off">Off</div>
          </div>
        </div>
      </div>
      <div class="ec-tools-sidebar-content">
        <h3>Dark mode</h3>
        <div class="ec-change-mode">
          <div class="ec-mode-switch">
            <div class="ec-mode-btn">Mode</div>
            <div class="ec-mode-on">On</div>
            <div class="ec-mode-off">Off</div>
          </div>
        </div>
      </div>
      <div class="ec-tools-sidebar-content">
        <h3>RTL mode</h3>
        <div class="ec-change-rtl">
          <div class="ec-rtl-switch">
            <div class="ec-rtl-btn">Rtl</div>
            <div class="ec-rtl-on">On</div>
            <div class="ec-rtl-off">Off</div>
          </div>
        </div>
      </div>
      <div class="ec-tools-sidebar-content">
        <h3>Clear local storage</h3>
        <a class="clear-cach" href="javascript:void(0)">Clear Cache & Default</a>
      </div>
    </div>
  </div>
  <x-category></x-category>

@endsection
