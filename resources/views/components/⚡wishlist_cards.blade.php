<?php

use Livewire\Component;
use App\Models\{Product, Cart, Favoriate};
new class extends Component {
    public ?Product $product = null;
    public $favoriate;
    public $products_offer ;
    public function mount($products_offer){
        $this->products_offer  =$products_offer;
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
    }
    public function deleteFromFavoriate(Product $product)
    {
        $wishlist = auth()->user()->favoriates()->where("product_id", $product->id)->first();
        $wishlist->delete();
        app(\App\Services\StorefrontData::class)->forgetFavoriates();
        flash()->success("Product Has Been Deleted");
    }
};
?>
@inject('offerService', 'App\Services\OfferService')

<div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-6  ec-product-content" wire:key="{{ $product->id }}">
    @if($product)
        <div class="ec-product-inner">
            <div class="ec-pro-image-outer">
                <div class="ec-pro-image">
                    <a href="product-left-sidebar.html" class="image">
                        <x-record-image :src="$product->image?->path" :alt="$product->name" class="main-image" height="300px" />
                        <x-record-image :src="$product->image?->path" :alt="$product->name" class="hover-image" height="300px" />
                    </a>
                    @if($product->has_discount)
                        <span class="percentage">${{ $product->price - $product->discount_price  }}</span>
                    @endif
                    <a href="#" wire:click="$dispatch('loadProduct', { id: {{ $product->id }} })" class="quickview"
                        data-link-action="quickview" title="Quick view" data-bs-toggle="modal"
                        data-bs-target="#ec_quickview_modal"><i class="fi-rr-eye"></i></a>
                    <div class="ec-pro-actions">
                        <a href="compare.html" class="ec-btn-group compare" title="Compare"><i
                                class="fi fi-rr-arrows-repeat"></i></a>
                        <button wire:click="addCart({{ $product->id }})" class="add-to-cart"><i
                                class="fi-rr-shopping-basket"></i></button>
                        <button class="ec-btn-group wishlist text-danger"
                            wire:click='deleteFromFavoriate({{ $product->id }})' title="Wishlist">X</button>
                    </div>
                </div>
            </div>
            <div class="ec-pro-content">
                <h5 class="ec-pro-title"><a href="{{ route("showProduct", $product->id) }}">{{ $product->name }}</a>
                </h5>
                <div class="d-flex">
                    <span>Price :</span>
                    <span class="ec-price">
                        @if($product->price == $offerService->getDiscount($product , $products_offer))
                            <span class="new-price ">${{ $product->price }}</span>
                        @else
                            <span class="old-price text-danger fs-5 pl-2">{{ $product->price }}</span>
                            <span class="new-price  fs-5">{{ $offerService->getDiscount($product , $products_offer)["best"] }}</span>
                        @endif
                    </span>
                </div>

            </div>
        </div>
    @endif
</div>
