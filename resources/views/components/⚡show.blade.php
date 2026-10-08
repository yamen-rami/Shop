<?php

use Livewire\Component;
use Livewire\Attributes\{Computed ,On};
use App\Services\OfferService;
use App\Models\{Product, Cart , Offer};
new class extends Component {
    //
    public ?Product $selectedProduct = null;
    public function offers(){
        return app(\App\Services\StorefrontData::class)->offers();
    }
    #[Computed]
    public function cart(){
        return app(\App\Services\StorefrontData::class)->cart();
    }
    #[On('loadProduct')]
    public function loadProduct($id)
    {
        // $this->cart = auth()->user()->cart()->first();
        $this->selectedProduct = Product::with('image')->findOrFail($id);
        $this->dispatch('product-loaded');
    }
    public function getValue($product){
        return app(OfferService::class)->getDiscount($product , $this->offers());
    }
    public function addCart(Product $product)
    {
        if (!auth()->check()) {
            return redirect()->route("login");
        }
        $cart = Cart::firstOrCreate([
            "user_id" => auth()->id(),
        ]);
        if ($cart->created_at->lte(now()->subDay())) {
            $cart->forceFill(['created_at' => now()])->save();
        }
        $exsistingProduct = $cart->products()->where("product_id", $product->id)->first();
        if ($exsistingProduct) {
            $cart->products()->updateExistingPivot($product->id, [
                "quantity" => $exsistingProduct->pivot->quantity + 1,
            ]);
            flash()->success("Product Has Added To The Cart for the " . ($exsistingProduct->pivot->quantity + 1));
        } else {
            $cart->products()->attach($product->id, [
                "quantity" => 1
            ]);
            flash()->success("Product Has Added To The Cart");
        }
        app(\App\Services\StorefrontData::class)->forgetCart();
        $this->dispatch("cart-updated");
        // Tell every page to rerender itslef
    }

};
?>
<div>
    <div class="modal fade" id="ec_quickview_modal" tabindex="-1" aria-label="Product quick view" wire:ignore.self
        x-data x-on:product-loaded.window="$nextTick(() => bootstrap.Modal.getOrCreateInstance($el).show())">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close qty_close" data-bs-dismiss="modal" aria-label="Close"></button>
                @if($selectedProduct)
                    @php($discount = $this->getValue($selectedProduct))
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5">
                                <x-record-image :src="$selectedProduct->image?->path" :alt="$selectedProduct->name" class="img-fluid" />
                            </div>
                            <div class="col-md-7">
                                <h5>{{ $selectedProduct->name }}</h5>
                                <p>{{ Str::limit($selectedProduct->desc, 100) }}</p>
                                <p><strong>${{ $discount['best'] }}</strong></p>
                                <a href="{{ route('showProduct', $selectedProduct) }}">View product</a>
                                <div class="ec-quickview-cart mt-3">
                                    <button class="btn btn-primary" data-bs-dismiss="modal" wire:click="addCart({{ $selectedProduct->id }})">Add to cart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
