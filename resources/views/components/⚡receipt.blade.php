<?php

use Livewire\Component;
use App\Models\{Offer, Product, Cart};
use App\Services\{CartService, OfferService};
use Livewire\Attributes\{Computed, On};
new class extends Component {
    public $globalCart;
    public $globalOffer;
    public $product;
    public $code = null;
    public function mount($globalCart, $globalOffer)
    {
        $this->globalOffer = $globalOffer;
        $this->globalCart = $globalCart;
        $this->product = auth()->user()->cart->products()->first();
    }
    public function addCoupon()
    {
        $this->dispatch("code-applied", code: $this->code);
    }
    #[Computed]
    public function totalPrice()
    {
        if (!$this->globalCart->products) {
            return;
        }
        return app(CartService::class)->totalPrice($this->globalCart->products, $this->globalOffer , $this->code);
    }
    #[Computed]
    public function originalPrice()
    {
        if (!$this->globalCart->products) {
            return;
        }
        return app(CartService::class)->originalPrice($this->globalCart->products);
    }
    #[Computed]
    public function discountTotal()
    {

        if (!$this->globalCart->products) {
            return;
        }
        return app(CartService::class)->discountTotal($this->globalCart->products, $this->globalOffer , $this->code);
    }
    #[on('cart-updated')]
    public function resetAll()
    {
        unset($this->totalPrice);
        unset($this->discountTotal);
        unset($this->originalPrice);
    }
};
?>

{{-- Do what you can, with what you have, where you are. - Theodore Roosevelt --}}
<div class="ec-cart-rightside col-lg-4 col-md-12">
    <div class="ec-sidebar-wrap">
        <!-- Sidebar Summary Block -->
        <div class="ec-sidebar-block">
      
          

            <div class="ec-sb-block-content">
                <div class="ec-cart-summary-bottom">
                    <div class="ec-cart-summary">
                        <div>
                            <span class="text-left">{{ __("home.original") }}</span>
                            <span class="text-right">${{ $this->originalPrice }}</span>
                        </div>
                        <div>
                            <span class="text-left">{{ __("home.discount") }}</span>
                            <span class="text-right">${{ $this->discountTotal }}</span>
                        </div>
                        
                        <div>
                            <span class="text-left">{{ __("home.coupon") }}</span>
                            <span class="text-right"><a class="ec-cart-coupan">{{ __("home.applyCoupon") }}</a></span>
                        </div>
                        <div class="ec-cart-coupan-content">
                            <div class="ec-cart-coupan-form" name="ec-cart-coupan-form">
                                <input class="ec-coupan" type="text" wire:model='code' required=""
                                    placeholder="{{ __("home.couponSearch") }}" name="ec-coupan" value="">
                                <button class="ec-coupan-btn button btn-primary" type="submit" name="subscribe"
                                    wire:click='addCoupon' value="">{{ __("home.buttonSearch") }}</button>
                            </div>

                        </div>
                        <div class="ec-cart-summary-total">
                            <span class="text-left">{{ __("home.total") }}</span>
                            <span class="text-right">
                                ${{ $this->totalPrice }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Sidebar Summary Block -->
    </div>
</div>