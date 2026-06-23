<?php

use Livewire\Component;
use App\Models\Cart;
use Livewire\Attributes\{On, Computed};

new class extends Component
{
    #[Computed]
    public function cart(){
        return Cart::with("products")->valid()->first();
    }
    public function getCount(){
        if (!$this->cart()) {
            return 0;
        }
        // get the cart total 
        return $this->cart()->products->sum(function($product){
            return $product->pivot->quantity;
        });
    }
    #[On('cart-updated')]
    public function refreshCart(){
        unset($this->cart);
    }
};
?>

<span>
    {{ $this->getCount() }}
</span>