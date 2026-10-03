<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Services\OfferService;
use App\Models\{Product, Cart, Favoriate, Offer};
new class extends Component {
    public $product;
    public $favoriate;
    public $products;
    public $products_offer;
    public function mount($products_offer , $product)
    {
        $this->product = $product; 
        $this->products_offer = $products_offer;
    }
    #[Computed]
    public function offerService()
    {
        return app(OfferService::class);
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
    public function addFavoriate(Product $product)
    {
        if (!auth()->check()) {
            return redirect()->route("login");
        }
        auth()->user()->favoriates()->firstOrCreate([
            "product_id" => $product->id,
        ]);
        flash()->success("Product Has Added To Which List");
    }

};
?>

@php
    $result = $this->offerService->getDiscount($product, $this->products_offer);
    $price = $result["best"];
    $offer = $result["offer"];
@endphp
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-6  ec-product-content" wire:key="{{ $product->id }}">
    <div class="ec-product-inner">
        <div class="ec-pro-image-outer">
            <div class="ec-pro-image">
                <a href="product-left-sidebar.html" class="image">
                    <img class="main-image" height="300px" src="{{ asset($product->image) }}" alt="Product" />
                    <img class="hover-image" height="300px" src="{{ asset($product->image) }}" alt="Product" />
                </a>
                @if($offer)
                    <span class="percentage">{{ $this->offerService->offerType($offer)   }}</span>
                @endif
                <a href="#" wire:click="$dispatch('loadProduct', { id: {{ $product->id }} })" class="quickview"
                    data-link-action="quickview" title="Quick view" data-bs-toggle="modal"
                    data-bs-target="#ec_quickview_modal"><i class="fi-rr-eye"></i></a>
                <div class="ec-pro-actions">
                    <a href="#" class="ec-btn-group compare" title="Compare"><i
                            class="fi fi-rr-arrows-repeat"></i></a>
                    <button wire:click="addCart({{ $product->id }})" class="add-to-cart"><i
                            class="fi-rr-shopping-basket"></i></button>
                    <button class="ec-btn-group wishlist" wire:click='addFavoriate({{ $product->id }})'
                        title="Wishlist">
                        <i class="fi-rr-heart"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="ec-pro-content">
            <h5 class="ec-pro-title"><a wire:navigate    href="{{ route("showProduct", $product->id) }}">{{ $product->name }}</a></h5>
            <div class="d-flex align-items-center">
                <span  class="new-price fs-6">{{ __("home.price") }} :</span>
                <span class="ec-price">
                    @if($product->price === $result["best"])
                        <span class="new-price ">${{ $product->price }}</span>
                    @else
                        <span class="old-price text-danger fs-5 pl-2">${{ $product->price }}</span>
                        <span class="new-price  fs-5">${{ $result["best"] }}</span>
                    @endif
                </span>
            </div>
            <livewire:add-to-cart :product="$product" />
        </div>
    </div>
</div>