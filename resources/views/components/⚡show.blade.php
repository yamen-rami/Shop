<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{Product, Cart};
new class extends Component {
    //
    public ?Product $selectedProduct = null;
    public ?Cart $cart = null;
    #[On('loadProduct')]
    public function loadProduct($id)
    {
        // $this->cart = auth()->user()->cart()->first();
        $this->selectedProduct = Product::findOrFail($id);
    }
    public function addCart(Product $product)
    {
        if (!auth()->check()) {
            return redirect()->route("login");
        }
        $cart = Cart::firstOrCreate([
            "user_id" => auth()->id(),
        ]);
        $exsistingProduct = $cart->products()->where("product_id", $product->id)->first();
        if ($exsistingProduct) {
            $cart->products()->updateExistingPivot($product->id, [
                "quantity" => $exsistingProduct->pivot->quantity + 1,
            ]);
            flash()->success("Product Has Added To The Cart for the " . $exsistingProduct->pivot->quantity + 1);
        } else {
            $cart->products()->attach($product->id, [
                "quantity" => 1
            ]);
            flash()->success("Product Has Added To The Cart");
        }
        $this->dispatch("cart-updated");
        // Tell every page to rerender itslef
    }

};
?>
<div>

    @if($this->selectedProduct)
            <div class="modal fade" id="ec_quickview_modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <button type="button" class="btn-close qty_close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-5 col-sm-12 col-xs-12">

                                    <div class="qty-nav-thumb">
                                        <div class="qty-slide">
                                            <img class="img-responsive" height="300px" src="{{ $this->selectedProduct->image }}"
                                                alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="quickview-pro-content">
                                        {{-- NAME --}}
                                        <h5 class="ec-quick-title"><a href="product-left-sidebar.html"></a>
                                            <span>Name : </span>
                                            {{ $this->selectedProduct->name }}
                                        </h5>
                                        {{-- Desc --}}
                                        <div class="ec-quickview-desc">
                                            <span>Desc : {{ Str::limit($this->selectedProduct->desc, 100) }}</span>
                                        </div>
                                        <div class="ec-quickview-price">
                                            @if($this->selectedProduct->price === $this->selectedProduct->discount_price)
                                                <span class="new-price">${{ $this->selectedProduct->discount_price }}</span>
                                            @else
                                                <span class="old-price">${{ $this->selectedProduct->price }}</span>
                                                <span class="new-price">${{ $this->selectedProduct->discount_price }}</span>
                                            @endif
                                        </div>
                                        <div class="ec-quickview-price">

                                            <livewire:increment_decrement :key="'modal-product-' . $this->selectedProduct->id"
                                                :product="$this->selectedProduct" />
                                        </div>

                                        <div class="ec-quickview-cart ">
                                            <button class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close"
                                                wire:click='addCart({{ $this->selectedProduct->id }})'>
                                                <i class="fi-rr-shopping-basket"></i>Add To Cart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>