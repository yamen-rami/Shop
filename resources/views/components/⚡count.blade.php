<?php

use Livewire\Component;
use App\Models\Cart;
use Livewire\Attributes\{On, Computed};

new class extends Component {
    #[Computed()]
    public function cart()
    {
        return app(\App\Services\StorefrontData::class)->cart();
    }
    #[Computed()]
    public function getCount()
    {
        return app(\App\Services\StorefrontData::class)->cartCount();
    }
    #[on("cart-updated")]
    public function refreshCart()
    {
        app(\App\Services\StorefrontData::class)->forgetCart();
        unset($this->cart);
        unset($this->getCount);
    }
};
?>

<span>
    {{ $this->getCount }}
</span>
