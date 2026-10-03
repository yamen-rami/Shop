<?php

use Livewire\Component;
use Livewire\Attributes\{Computed, Locked};
use App\Services\OfferService;
use App\Models\{Product, Cart};
use Illuminate\Database\Eloquent\Collection;
new class extends Component {
    #[Locked]
    public Collection $products;
    #[Locked]
    public bool $wishlist = false;
    #[Locked]
    public Collection $products_offer;
    public function mount(Collection $products, Collection $products_offer, bool $wishlist = false)
    {
        $this->wishlist = $wishlist;
        $this->products = $products;
        $this->products_offer = $products_offer;
    }
    public function hydrate()
    {
        $this->products_offer->loadMissing(['products', 'categories']);
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
    }
    public function addFavoriate(Product $product)
    {
        if (!auth()->check()) {
            return redirect()->route("login");
        }
        auth()->user()->favoriates()->firstOrCreate([
            "product_id" => $product->id,
        ]);
        app(\App\Services\StorefrontData::class)->forgetFavoriates();
        flash()->success("Product Has Added To Which List");
    }

    public function deleteFromFavoriate(Product $product)
    {
        abort_unless(auth()->check(), 403);
        auth()->user()->favoriates()->where('product_id', $product->id)->delete();
        $this->products = $this->products->reject(fn ($item) => $item->id === $product->id)->values();
        app(\App\Services\StorefrontData::class)->forgetFavoriates();
        $this->dispatch('wishlist-updated');
    }
};
?>

<div class="col-12">
<div class="row">
@foreach($this->products as $product)
@php
    $result = $this->offerService->getDiscount($product, $this->products_offer);
    $price = $result["best"];
    $offer = $result["offer"];
@endphp
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-6  ec-product-content" wire:key="card-{{ $product->id }}">
    <div class="ec-product-inner">
        <div class="ec-pro-image-outer">
            <div class="ec-pro-image">
                <a href="{{ route('showProduct', $product) }}" class="image">
                    <img class="main-image" height="300px" src="{{ str_starts_with($product->image, 'assets/') ? asset($product->image) : asset('storage/' . $product->image) }}" alt="Product" />
                    <img class="hover-image" height="300px" src="{{ str_starts_with($product->image, 'assets/') ? asset($product->image) : asset('storage/' . $product->image) }}" alt="Product" />
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
                    <button class="ec-btn-group wishlist" wire:click="{{ $wishlist ? 'deleteFromFavoriate' : 'addFavoriate' }}({{ $product->id }})"
                        title="{{ $wishlist ? 'Remove from wishlist' : 'Add to wishlist' }}">
                        <i class="fi-rr-heart"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="ec-pro-content">
            <h5 class="ec-pro-title"><a href="{{ route("showProduct", $product->id) }}">{{ $product->name }}</a></h5>
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
            <div class="ec-quickview-cart">
                <button type="button" class="btn btn-primary" wire:click="addCart({{ $product->id }})">
                    <i class="fi-rr-shopping-basket"></i><span class="ml-3">{{ __("home.addToCart") }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
</div>
</div>
