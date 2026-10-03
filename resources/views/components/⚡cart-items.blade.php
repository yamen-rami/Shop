<?php

use Livewire\Component;
use App\Models\{Cart, Product};
new class extends Component {
    public $globalCart;
    public $offers ;
    public function mount($globalCart , $offers)
    {
        dd("hello");
        $this->offers = $offers ;
        $this->globalCart = $globalCart;
    }
    
};
?>
{{-- @inject("offerService", "App\Services\OfferService")/ --}}
<div>
    {{-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison --}}
    @foreach($this->globalCart->products as $product)
        <tr>
            <button wire:click='there'>THere</button>
            <td data-label="Product" class="ec-cart-pro-name"><a href="product-left-sidebar.html"><img
                        class="ec-cart-pro-img mr-4" height="60px" src="{{ asset($product->image) }}"
                        alt="" />{{ $product->name }}</a></td>
            <td data-label="Price" class="ec-cart-pro-price"><span class="amount">${{ $product->price}}</span></td>
            <td data-label="Quantity" class="ec-cart-pro-qty" style="text-align: center;">
                <livewire:increment_decrement :product="$product->id" />
            </td>
            <td data-label="Total" class="ec-cart-pro-subtotal">${{ $offerService->getDiscount($product , $this->offers) }}</td>
            <td data-label="Remove" wire:click='deleteProduct({{ $product->id }})' class="ec-cart-pro-remove">
                <a href="#"><i class="ecicon eci-trash-o"></i></a>
            </td>
        </tr>
    @endforeach
</div>