<?php

use Livewire\Component;
use App\Models\{Cart, Product};
use Livewire\Attributes\{Computed, On};

new class extends Component {

    public int $productId;
    public int $quantity = 0;
    public function mount(Product|int $product , ?Cart $globalCart = null)
    {
        $this->productId = $product instanceof Product ? $product->id : $product;
        $this->updateQuantityState();
    }

    #[On('loadProduct')]
    public function handleProductChanged($id)
    {
        $this->productId = (int) $id;
        // Clear computed cache only when the product ID changes explicitly
        unset($this->cart);
        $this->updateQuantityState();
    }

    #[On("cart-updated")]
    public function resetQuantity(){
        app(\App\Services\StorefrontData::class)->forgetCart();
        unset($this->cart);
        $this->updateQuantityState();

    }
    #[Computed()]
    public function cart()
    {
        // Eloquent automatically loads pivot details when accessing via belongsToMany relationships
        return app(\App\Services\StorefrontData::class)->cart();
    }

    public function updateQuantityState()
    {
        if ($this->cart) {
            $cartProduct = $this->cart->products->firstWhere('id', $this->productId);
            if ($cartProduct && $cartProduct->pivot) {
                $this->quantity = $cartProduct->pivot->quantity;
                return;
            }
        }

        $this->quantity = 0;
    }

    public function increment()
    {
        if (!$this->cart) return;

        // Fix: Read from the preloaded collection array in memory instead of calling ->products()
        $cartProduct = $this->cart->products->firstWhere('id', $this->productId);

        if ($cartProduct) {
            $newQuantity = $cartProduct->pivot->quantity + 1;

            if ($newQuantity > 20) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "quantity" => ["You can't add more than 20 products"]
                ]);
            }

            // Perform direct background sync mutation
            $this->cart->products()->updateExistingPivot($this->productId, [
                'quantity' => $newQuantity,
            ]);
        } else {
            $this->cart->products()->attach($this->productId, ['quantity' => 1]);
        }

        // Force a fresh reload of the cart relationship state array for UI consistency
        app(\App\Services\StorefrontData::class)->forgetCart();
        unset($this->cart);
        $this->updateQuantityState();
        $this->dispatch("cart-updated");
    }

    public function decrement()
    {
        if (!$this->cart) return;

        // Fix: Read from memory array cache
        $cartProduct = $this->cart->products->firstWhere('id', $this->productId);

        if (!$cartProduct) return;

        $newQuantity = $cartProduct->pivot->quantity - 1;

        if ($newQuantity <= 0) {
            $this->cart->products()->detach($this->productId);
        } else {
            $this->cart->products()->updateExistingPivot($this->productId, [
                'quantity' => $newQuantity,
            ]);
        }

        app(\App\Services\StorefrontData::class)->forgetCart();
        unset($this->cart);
        $this->updateQuantityState();
        $this->dispatch("cart-updated");
    }
};
?>

<div>
    <li class="d-flex align-center">
        <div class="bg-light text-dark">
            <button wire:click="decrement" class="btn btn-sm text-black px-2 py-1" type="button">-</button>
            <span class="fw-medium px-2 text-heading">{{ $quantity }}</span>
            <button wire:click="increment" class="btn px-2 py-1" type="button">+</button>
        </div>
    </li>
</div>
