<?php

use Livewire\Component;
use App\Models\Cart;
use Livewire\Attributes\{On, Computed};

new class extends Component {
    #[Computed()]
    public function cart()
    {
        return Cart::with("products")->valid()->first();
    }
    #[Computed()]
    public function getCount()
    {
        if (!auth()->check()) {
            return 0;
        }

        if (!$this->cart) {
            return 0;
        }
        if ($this->cart->products->count() > 0) {
            return $this->cart->products->sum(function ($product) {
                return $product->pivot->quantity;
            });
        }
    }
    #[on("cart-updated")]
    public function refreshCart()
    {
        unset($this->cart);
        unset($this->getCount);
    }
};
?>

<span>
    {{ $this->getCount }}
</span>