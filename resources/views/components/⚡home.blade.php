<?php

use Livewire\Component;
use Livewire\Attributes\{On, Computed};
use App\Models\{Product, Cart, Offer};
use App\Services\CartService;
new class extends Component {

  #[Computed]
  public function cart()
  {
    return Cart::with("products")->where("user_id", auth()->id())
      ->valid()->first();
  }

  #[Computed]
  public function getCount()
  {
    if (!$this->cart) {
      return 0;
    }
    // ✅ Use collection sum instead of loop
    return $this->cart->products->sum('pivot.quantity');
  }

  #[Computed]
  public function totalPrice()
  {
    if (!$this->cart) {
      return 0;
    }
    $offers = Offer::with("products")->active()->get();
    return app(CartService::class)->totalPrice($this->cart->products, $offers);
    // ✅ Use collection sum with closure
    return $this->cart->products->sum(fn($product) => $product->discount_price * $product->pivot->quantity);
  }

  #[Computed]
  public function originalPrice()
  {
    if (!$this->cart) {
      return 0;
    }
    // return $this->cart->products->sum(fn($product) => $product->price * $product->pivot->quantity);
    return app(CartService::class)->originalPrice($this->cart->products);
  }

  public function increment($productId)
  {
    if (!$this->cart) {
      return;
    }

    // ✅ Use firstWhere on the already-loaded collection (NO QUERY!)
    $product = $this->cart->products->firstWhere('id', $productId);

    if (!$product) {
      return;
    }

    $newQuantity = $product->pivot->quantity + 1;

    if ($newQuantity > 20) {
      throw \Illuminate\Validation\ValidationException::withMessages([
        "quantity" => ["You can't add more than 20 products"]
      ]);
    }

    // ✅ Use $this->cart directly (NO EXTRA QUERY!)
    $this->cart->products()->updateExistingPivot($productId, [
      'quantity' => $newQuantity,
    ]);

    unset($this->cart);
    $this->dispatch("cart-updated");
  }

  public function decrement($productId)
  {
    if (!$this->cart) {
      return;
    }

    // ✅ Use firstWhere on the already-loaded collection (NO QUERY!)
    $product = $this->cart->products->firstWhere('id', $productId);

    if (!$product) {
      return;
    }

    $newQuantity = $product->pivot->quantity - 1;

    if ($newQuantity <= 0) {
      $this->cart->products()->detach($productId);
      unset($this->cart);
      $this->dispatch("cart-updated");
      return;
    }

    // ✅ Use $this->cart directly (NO EXTRA QUERY!)
    $this->cart->products()->updateExistingPivot($productId, [
      'quantity' => $newQuantity,
    ]);

    unset($this->cart);
    $this->dispatch("cart-updated");
  }

  #[On('cart-updated')]
  public function refreshCart()
  {
    // ✅ Just unset - Livewire will reload it automatically when accessed
    unset($this->cart);
  }

  public function deleteProduct(Product $product)
  {
    if (!$this->cart) {
      return;
    }

    $this->cart->products()->detach($product->id);

    unset($this->cart);
    $this->dispatch("cart-updated");
  }


}
?>
<div>
  <ul class="eccart-pro-items">
    @if($this->cart)
      @foreach($this->cart->products as $product)
        <li wire:key='{{ $product->id }}'>
          <a href="{{ route("showProduct", $product->id) }}" class="sidekka_pro_img"><img height="100px" width="150px"
              src="{{ asset($product->image) }}" alt="product"></a>
          <div class="ec-pro-content d-grid ">
            <div class="row">
              <div>
                <div class="d-flex justify-between">
                  <a href="{{ route("showProduct", $product->id) }}"
                    class="cart_pro_title">{{ Str::limit($product->name, 10) }}</a>
                  <button wire:click='deleteProduct({{ $product->id }})' class="text-danger">×</button>
                </div>
                <span class="cart-price"><span>{{ $product->discount_price }}</span>
                  </span>
                <div class="d-flex align-items-center gap-2 mt-2 mb-3">
                  <small class="text-danger me-2"></small>
                  <button wire:click="decrement({{ $product->id }})" class="btn btn-sm text-black  px-2 py-1" type="button">
                    -
                  </button>
                  <span class="fw-medium px-2 text-heading">
                    {{ $product->pivot->quantity }}
                  </span>
                  <button wire:click="increment({{ $product->id }})" class="btn  px-2 py-1">
                    +
                  </button>
                </div>
              </div>

            </div>
          </div>
        </li>
      @endforeach
    @endif
  </ul>
  <div class="ec-cart-bottom">
    <div class="cart-sub-total">
      <table class="table cart-table">
        <tbody>
          <tr>
            <td class="text-left">{{ __("home.original") }} :</td>
            <td class="text-right">${{ $this->originalPrice }}</td>
          </tr>
          <tr>
            <td class="text-left">{{ __("home.total") }} :</td>
            <td class="text-right primary-color">${{ $this->totalPrice }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="cart_btn">
      <a href="{{ route("checkout") }}" class="btn btn-primary">{{ __("home.viewCart") }}</a>
    </div>
  </div>
</div>