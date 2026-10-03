<div class="ec-side-cat-overlay"></div>
<div class="col-lg-3 category-sidebar" data-animation="fadeIn">
  <div class="cat-sidebar">
    <div class="cat-sidebar-box">
      <div class="ec-sidebar-wrap">
        <!-- Sidebar Category Block -->
        <div class="ec-sidebar-block">
          <div class="ec-sb-title">
            <h3 class="ec-sidebar-title">{{ __("home.categories") }}<button class="ec-close">×</button></h3>
          </div>
          <div class="ec-sb-block-content">
            {{--
            <livewire:category /> --}}
            <div>
              @foreach($globalCategories as $category)
                <ul>
                  <li>
                    <div class="ec-sidebar-block-item "><img src="{{ asset('assets/images/icons/dress-8.png') }}"
                        class="svg_img" alt="drink" />{{ $category->name }}</div>
                    <ul style="display: block;">
                      @foreach($category->products as $product)
                        <li>
                          <div class="ec-sidebar-sub-item"><a
                              href="{{ route("showProduct", $product->id) }}">{{ $product->name }} <span
                                title="Available Stock">{{ $product->discount_price }}</span></a>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  </li>
                </ul>
              @endforeach
            </div>
          </div>
        </div>
        <!-- Sidebar Category Block -->
      </div>
    </div>
  </div>
</div>