<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\{Cart, Product};
new class extends Component {
    // add to cart
    use WithPagination; // Enables Livewire's dynamic pagination system
    // public $products;
    public $count = 0;
    public function getProductsProperty()
    {
        return Product::with(["tags", "companies", "catagory"])->paginate(1);
    }
    public function addToCart(Product $product)
    {
        $cart = Cart::firstOrCreate([
            "user_id" => auth()->id(),
        ]);
        // TODO Flash Messages
        // filtering cart and then delete the cart who have been created since 1 day of course in the model 

        // cart 
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
        return redirect()->back();
    }
    // Getting the Cart Count
    public function getCount()
    {
        $this->count = app(\App\Services\StorefrontData::class)->cartCount();
        // foreach ($cart->products as $product) {

        // }
        return $this->count;
    }
};
?>

<div>
    <div>
        <div class="position-fixed bottom-0 end-0 m-4" style="z-index: 1050;">
            <div class="card shadow-lg  text-white rounded-pill px-4 py-2 border-0">
                <div class="d-flex align-items-center gap-2">
                    <span>🛒</span>
                    @if(($currentCount = $this->getCount()) > 0)
                        <a href="{{ route("checkout") }}" class="badge bg-info text-white fs-6 rounded-pill fw-bold">
                            {{ $currentCount }}
                        </a>
                    @else
                        <small class="text-muted text-nowrap">Cart Empty</small>
                    @endif
                </div>
            </div>
        </div>

        <section class="container mb-5 pt-5">
            <div class="d-grid g-4">
                <div class="row">
                    @forelse ($this->products as $product)
                        <div class="col-lg-4 g-5" wire:key="{{ $product->id }}">
                            <div class="card h-100">
                                <img style="height:300px;" class="card-img-top" src="{{ asset($product->image) }}"
                                    alt="Image For {{ $product->name }}" />
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">{{ $product->desc }}</p>

                                    <p class="card-text">
                                        <strong>Price: </strong>
                                        {{ $product->has_discount ? $product->discount_price : $product->price }}
                                    </p>
                                    <p class="card-text mb-3">
                                        <strong>Quantity: </strong> {{ $product->quantity }}
                                    </p>
                                    <button wire:click="addToCart({{ $product->id }})" class="btn btn-outline-primary">
                                        Add To Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-10 text-center py-5">
                            <p class="text-muted">No products available.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            {{ $this->products->links() }}
        </section>
    </div>
    {{-- <section class="container mb-5 pt-5">
        <div class="d-grid g-4 ">
            <div class="row">
                @forelse ($this->products as $product)
                <div class="col-lg-4 g-5" wire:key='$product->id'>
                    <div class="card h-100">
                        <img style="height:300px ;" class="card-img-top" src="{{ asset($product->image) }}"
                            alt="Image For {{ $product->name }}" />
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">
                                {{ $product->desc }}
                            </p>

                            <p class="card-text">
                                <strong>Price : </strong>
                                @if($product->has_discount)
                                {{ $product->discount_price }}
                                @else
                                {{ $product->price }}
                                @endif
                            </p>
                            <p class="card-text mb-3">
                                <strong>Quantity : </strong>
                                {{ $product->quantity }}
                            </p>
                            <button wire:click="addToCart({{ $product->id }})" class="btn btn-outline-primary">Add To
                                Cart</button>
                        </div>
                    </div>
                </div>
                @empty

                @endforelse

            </div>
            <div class="pt-5">
                <h1>

                    {{ $this->products->links()}}
                </h1>
            </div>
        </div>

    </section> --}}
    {{-- Knowing is not enough; we must apply. Being willing is not enough; we must do. - Leonardo da Vinci --}}
</div>
