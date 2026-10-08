<?php

use Livewire\Component;
use App\Models\{Cart, Product};
use Livewire\Attributes\{On, Computed};
use App\Services\OfferService;
new class extends Component {

    public $code;
    public $quantity  = 0 ;
    #[Computed]
    public function offers()
    {
        return app(\App\Services\StorefrontData::class)->offers();
    }
    #[Computed]
    public function globalCart()
    {
        return app(\App\Services\StorefrontData::class)->cart();
    }
    #[On('cart-updated')]
    public function refreshCart()
    {
        unset($this->globalCart, $this->calc);
    }
    #[Computed]
    public function calc()
    {
        $offerService = app(OfferService::class);

        $prices = [];
        foreach ($this->globalCart?->products ?? [] as $product) {
            $prices[$product->id] = $offerService->getCoupon($product, $this->offers, $this->code);
        }
        return $prices;
    }

    // Call this whenever the code or cart changes
    #[On("code-applied")]

    public function updateCoupon($code = null)
    {
        $this->code = $code;
        unset($this->calc);
    }
}

?>
@inject("offerService", "App\Services\OfferService")
<tbody>
    {{-- @dd($this->calc) --}}
    {{-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison --}}
    @forelse($this->globalCart?->products ?? [] as $product)
        <tr wire:key="product-{{ $product->id }}">
            <td  ><a href="product-left-sidebar.html"><x-record-image :src="$product->image?->path" :alt="$product->name" class="ec-cart-pro-img mr-4" height="60px" />{{ $product->name }}</a></td>
            <td data-label="Price" class="ec-cart-pro-price"><span class="amount">${{ $product->price}}</span></td>
            <td class="fs-6" data-label="Quantity" class="ec-cart-pro-qty" style="text-align: center;">
                <livewire:increment_decrement :product="$product->id" :key="'checkout-quantity-'.$product->id" />

            </td>
            @php
            $calc = $this->calc ;
            @endphp
            <td data-label="Offer Price" class="ec-cart-pro-subtotal">
                ${{ $calc[$product->id]['best'] }}
            </td>
            <td data-label="Total" class="ec-cart-pro-subtotal">
                {{ $offerService->offerType($calc[$product->id]["offer"]) ?? __("home.noOffer") }}
            </td>
            <form action="{{ route("deleteCartItem", $product->id) }}" method="POST">
                @method("DELETE")
                @csrf
                <td data-label="Remove"class="ec-cart-pro-remove">
                    <button type="delete"><i class="ecicon eci-trash-o"></i></button>
                </td>
            </form>

        </tr>
    @empty
        <tr><td colspan="6">Cart Empty</td></tr>
    @endforelse
</tbody>
