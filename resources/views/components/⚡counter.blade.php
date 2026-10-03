<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{Cart, Offer, Product};
use Livewire\Attributes\Computed;
new class extends Component {
  public $couponOffer;
  public $cart;
  public $offerCode;
  public $offer;
  public $quantity;

  public function mount()
  {
    $this->cart = auth()->user()->cart()->with('products.companies')->first();
    $this->offer = Offer::global()->active()->first();
  }
  #[Computed]
  public function originalPrice()
  {
    return $this->cart->products->sum(function ($product) {
      return $product->price * $product->pivot->quantity;
    });
  }
  #[Computed]
  public function totalPrice()
  {
    if (!$this->cart)
      return 0;
    $products = auth()->user()->cart->products();
    $baseCartTotal = $this->cart->products->sum(function ($product) {
      return $this->discountProduct($product->id) * $product->pivot->quantity;
    });
    return max(0, $baseCartTotal);
  }
  public function discountPrice()
  {
    if ($this->offer->discount_type === 'percentage') {
      return '%' . $this->offer->discount_value * 100;
    } else {
      return "$" . $this->offer->discount_value;
    }
  }

  public function increment($productId)
  {

    if (!$this->cart) {
      return;
    }
    $product = $this->cart->products()->find($productId);
    $newQuantity = $product->pivot->quantity + 1;
    $cart = auth()->user()->cart;
    $cart->products()->updateExistingPivot($productId, [
      'quantity' => $newQuantity,
    ]);
    unset($this->totalPrice); // clear computed cache
    unset($this->originalPrice); // clear computed cache

    $this->cart->load('products'); // Refresh values for computed properties
  }
  public function decrement($productId)
  {
    $product = $this->cart->products()->find($productId);
    $newQuantity = $product->pivot->quantity - 1;
    if ($newQuantity <= 0) {
      $cart = $this->cart->products()->detach($productId);
      return;
    }

    if ($product) {
      // substract the product
      $cart = auth()->user()->cart;
      // update the quantity
      $cart->products()->updateExistingPivot($productId, [
        'quantity' => $newQuantity,
      ]);
      unset($this->totalPrice);
      unset($this->originalPrice);
    }
  }
  public function discount($productId)
  {
    $product = auth()->user()->cart->products()->find($productId);

    $offer = Offer::where("is_active", true)->first();
    if ($offer) {
      return $this->discountProduct($productId) * $product->pivot->quantity;
    }
    return $this->discountProduct($productId) * $product->pivot->quantity;
  }
  public function getCoupon()
  {
    $coupon = Offer::coupons()->where("code", $this->couponOffer)->where("is_active", true)->first();
    if ($coupon) {
      $perviousOffer = $this->offer->discount_value ?? 0;
      if ($coupon->discount_value > $perviousOffer) {
        $this->offer = $coupon;
        unset($this->totalPrice);
        if ($coupon->discount_type === "percentage") {
          flash()->success("Offer Has Applied Succefully Discount: Price " . '%' . $coupon->discount_value * 100);
        } else {
          flash()->success("Offer Has Applied Succefully Discount: Price " . "$" . $coupon->discount_value);
        }
      } else {
        flash("The Current Offer is Better Than The Coupon Offer");
      }

    } else {
      flash()->error("Wrong Coupon Code");
    }
  }

  // Get The Discount Price For Each Product
  public function discountProduct($productId)
  {
    $product = Product::find($productId);
    // mean idea is getting $product->price * $discount_value > $discount_price then this code will run
    $current = $product->price - $this->offer?->discount_value;
    // price = 100 ; price 100 - 10 = 90 ;
    // disocunt = 100 ; price 100 - 5 = 95 ; 
    // dd($product->price - $this->offer?->discount_value < $product->discount_price);
    if ($this->offer?->is_active) {
      if (!empty($this->offer->catagory_id) && $product->catagory_id !== $this->offer->catagory_id) {
        return $product->discount_price;
      }
      if ($product) {
        $offerPrice = $this->offer?->discount_type === 'percentage'
          ? $product->price * (1 - $this->offer?->discount_value)
          : $product->price - $this->offer?->discount_value;

        if ($offerPrice < $product->discount_price) {

          if ($this->offer) {
            if ($this->offer->discount_type === "percentage") {
              return $product->price * (1 - $this->offer->discount_value);
            }
            return $product->price - $this->offer->discount_value;
          }
        }
      }
    }
    return $product->discount_price;

  }
}
?>
<div>

    <div class="pt-5"></div>
    <div class="pt-5 mt-5">
      <div id="wizard-checkout" class="bs-stepper wizard-icons wizard-icons-example">
        <div class="bs-stepper-content border-top">
          <div id="checkout-cart" class="content">
            <div class="row">
              <div class="col-xl-8 mb-6 mb-xl-0">
                @if ($this->offer && $this->offer->is_active)
                  <div class="alert alert-success alert-dismissible mb-4" role="alert">
                    <div class="d-flex gap-4">
                      <div class="alert-icon flex-shrink-0 rounded me-0">
                        <i class="icon-base ti tabler-percentage"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h5 class="alert-heading mb-1">{{ $this->offer->name }}</h5>
                        <ul class="list-unstyled mb-0">
                          @if ($this->offer->discount_type === 'percentage')
                            <li>%{{ $this->offer->discount_value * 100 }} For All Product
                              Prices</li>
                          @else
                            <li>Fixed Amount ${{ $this->offer->discount_value }} For All
                              Product Prices</li>
                          @endif
                        </ul>
                      </div>
                    </div>
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="alert"
                      aria-label="Close"></button>
                  </div>
                @endif

                <h5>My Shopping Cart ({{ $this->cart ? $this->cart->products->count() : 0 }})</h5>

                <ul class="list-group mb-4">
                  @if ($this->cart)
                    @foreach ($this->cart->products as $product)
                      <li class="list-group-item p-6" wire:key="cart-item-{{ $product->id }}">
                        <div class="d-flex gap-4">
                          <div class="flex-shrink-0 d-flex align-items-center">
                            <img src="{{ $product->image }}" alt="product image" class="w-px-100 rounded-xl" />
                          </div>
                          <div class="flex-grow-1">
                            <div class="row">
                              <div class="col-md-8">
                                <p class="me-3 mb-2">

                                  <a href="javascript:void(0)" class="fw-medium">
                                    <span class="text-heading">{{ $product->name }}</span>
                                  </a>
                                </p>
                                <div class="text-body-secondary mb-2 d-flex flex-wrap">
                                  <span class="me-1">Sold by:</span>
                                  @forelse ($product->companies as $p)
                                    <a class="me-4 text-light">{{ $p->name }}</a>
                                    <span class="badge bg-label-success">In
                                      Stock</span>
                                  @empty
                                    <a class="me-4">Anonymous</a>
                                    <span class="badge bg-label-success">In
                                      Stock</span>
                                  @endforelse
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-2 mb-3">
                                  <small class="text-body-secondary me-2">QTY:</small>
                                  <button wire:click="decrement({{ $product->id }})"
                                    class="btn btn-sm btn-label-secondary px-2 py-1" type="button">
                                    <i class="icon-base ti tabler-minus icon-xs"></i>
                                  </button>

                                  <span class="fw-medium px-2 text-heading">
                                    {{ $product->pivot->quantity }}
                                  </span>

                                  <button wire:click="increment({{ $product->id }})"
                                    class="btn btn-sm btn-label-secondary px-2 py-1" type="button">
                                    <i class="icon-base ti tabler-plus icon-xs"></i>
                                  </button>
                                </div>
                              </div>

                              <div class="col-md-4">
                                <div class="text-md-end">
                                  <div class="my-2 mt-md-6 mb-md-4">
                                    @if ($product->has_discount)
                                      <span class="text-primary">${{ $this->discountProduct($product->id)}}/</span>
                                      <s class="text-body">${{ $product->price }}</s>
                                    @else
                                      <span class="text-primary">${{ $product->price }}</span>
                                    @endif
                                  </div>
                                  <div class="my-2 mt-md-6 mb-md-4">
                                    <span class="text-primary"><strong>Total Price:
                                        ${{ $this->discount($product->id)}}</strong></span>
                                  </div>

                                  <form method="POST" id="delete_cart_{{ $product->id }}"
                                    action="{{ route('cart.destroy', $product->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="badge bg-label-danger me-1 border-0">Delete</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    @endforeach
                  @endif
                </ul>


                <div class="list-group">
                  <a href="javascript:void(0)"
                    class="list-group-item text-primary border-primary d-flex justify-content-between">
                    <span class="fw-medium">Add More Products From Wishlist</span>
                    <i class="icon-base ti tabler-arrow-right icon-xs scaleX-n1-rtl mt-50"></i>
                  </a>
                </div>
              </div>

              <div class="col-xl-4">
                <div class="border rounded p-6 mb-4">
                  <!-- Offer -->
                  <h6>Offer</h6>
                  <div class="row g-4 mb-4">
                    <div class="col-8 col-xxl-8 col-xl-12">
                      <input type="text" wire:model='couponOffer' class="form-control" placeholder="Enter Promo Code"
                        aria-label="Enter Promo Code" />
                    </div>
                    <div class="col-4 col-xxl-4 col-xl-12">
                      <div class="d-grid">
                        <button type="button" wire:click='getCoupon' class="btn btn-label-primary">Apply</button>
                      </div>
                    </div>
                  </div>
                  <div class="border rounded p-6 mb-4">
                    <h6>Price Details</h6>
                    <dl class="row mb-0 text-heading">
                      <dt class="col-6 fw-normal">Original Price</dt>
                      <dd class="col-6 text-end">${{ $this->originalPrice }}</dd>

                      <dt class="col-6 fw-normal">Total Price</dt>
                      <dd class="col-6 text-end text-danger">${{ $this->totalPrice }}</dd>

                      @if ($this->offer)
                        <dt class="col-6 fw-normal">Coupon Discount</dt>
                        <dd class="col-6 text-end">
                          <a href="javascript:void(0)">
                            @if ($this->offer->discount_type === 'percentage')
                              %{{ $this->offer->discount_value * 100 }}
                            @else
                              ${{ $this->offer->discount_value }}
                            @endif
                          </a>
                        </dd>

                        <dt class="col-6 fw-normal">Total Discount</dt>
                        <dd class="col-6 text-end">${{ $this->originalPrice - $this->totalPrice }}
                        </dd>
                      @endif

                      <dt class="col-6 fw-normal">Order Total</dt>
                      <dd class="col-6 text-end">${{ $this->totalPrice }}</dd>

                      <dt class="col-6 fw-normal">Delivery Charges</dt>
                      <dd class="col-6 text-end">
                        <span class="badge bg-label-success ms-1">FREE</span>
                      </dd>
                    </dl>
                    <hr class="mx-n6 my-6" />
                    <dl class="row mb-0">
                      <dt class="col-6 text-heading">Total</dt>
                      <dd class="col-6 fw-medium text-end text-heading mb-0">${{ $this->totalPrice }}
                      </dd>
                    </dl>
                  </div>
                  <div class="d-grid">
                    <button class="btn btn-primary btn-next">Place Order</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


</div>
