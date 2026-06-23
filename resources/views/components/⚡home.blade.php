<?php

use Livewire\Component;
use Livewire\Attributes\{On, Computed};
use App\Models\{Product, Cart};
new class extends Component {
  #[Computed()]
  public function products()
  {
    return Product::with(["tags", "catagory", "companies", "catagoryOffer", "globalOffer"])
      ->where("quantity", ">", 0)
      ->get();
  }

  #[Computed()]
  public function cart()
  {
    return Cart::with("products")->valid()
      ->first();
  }


  #[Computed()]
  public function getCount()
  {
    $count = 0;
    if (!$this->cart) {
      return;
    }
    foreach ($this->cart->products as $product) {
      $count += $product->pivot->quantity;
    }

    return $count;
  }
  #[Computed()]

  public function totalPrice()
  {
    if (!$this->cart) {
      return;
    }
    return $this->cart->products->sum(function ($product) {
      return $product->discount_price * $product->pivot->quantity;
    });
  }

  #[Computed()]
  public function originalPrice()
  {
    if (!$this->cart) {
      return;
    }
    return $this->cart->products->sum(function ($product) {
      return $product->price * $product->pivot->quantity;
    });
  }

  public function increment($productId)
  {
    if (!$this->cart) {
      return;
    }
    $product = $this->cart->products()->find($productId);
    $newQuantity = $product->pivot->quantity + 1;
    $cart = auth()->user()->cart;
    if ($newQuantity > 20) {
      throw Illuminate\Validation\ValidationException::withMessages(["20" => ["You can't add more than 20 products"]]);
    }
    $cart->products()->updateExistingPivot($productId, [
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
    $product = $this->cart->products()->find($productId);
    $newQuantity = $product->pivot->quantity - 1;
    if ($newQuantity <= 0) {
      $cart = $this->cart->products()->detach($productId);
      unset($this->cart);
      return;
    }


    if ($product) {
      $cart = auth()->user()->cart;
      $cart->products()->updateExistingPivot($productId, [
        'quantity' => $newQuantity,
      ]);
      $this->dispatch("cart-updated");

    }
    unset($this->cart);
  }
  #[On('cart-updated')]
  public function refreshCart()
  {
    unset($this->cart);
    if ($this->cart) {
      $this->cart->load("products");
    }
  }
  public function deleteProduct(Product $product)
  {
    $this->cart->products()->detach($product->id);
    flash()->success(" $product->name has deleted Succefully");
    $this->dispatch("cart-updated");
  }
};
?>
<div>
  <ul class="eccart-pro-items">
    @if($this->cart)
      @foreach($this->cart->products as $product)
        <li wire:key='{{ $product->id }}'>
          <a href="{{ route("showProduct", $product->id) }}" class="sidekka_pro_img" ><img
            height="100px" width="150px"  src="{{ asset($product->image) }}" alt="product"></a>
          <div class="ec-pro-content d-grid ">
            <div class="row">
              <div>
                <a href="{{ route("showProduct", $product->id) }}" class="cart_pro_title">{{ $product->name }}</a>
                <span class="cart-price"><span>{{ $product->discount_price }}</span> X
                  {{ $product->pivot->quantity}}</span>
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
              
                <button wire:click='deleteProduct({{ $product->id }})' class="text-danger">×</button>
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
            <td class="text-left">Original Price :</td>
            <td class="text-right">{{ $this->originalPrice() }}</td>
          </tr>
          <tr>
            <td class="text-left">Total :</td>
            <td class="text-right primary-color">{{ $this->totalPrice() }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="cart_btn">
      <a href="{{ route("checkout") }}" class="btn btn-primary">View Cart</a>
      <a href="checkout.html" class="btn btn-secondary">Checkout</a>
    </div>
  </div>
</div>