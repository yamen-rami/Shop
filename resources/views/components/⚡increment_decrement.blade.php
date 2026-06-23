<?php

use Livewire\Component;
use App\Models\{Cart, Product};
use Livewire\Attributes\{Computed, On};

new class extends Component {
    
    public int $productId;
    public int $quantity = 0;

    public function mount(Product $product)
    {
        $this->productId = $product->id;
        $this->updateQuantityState();
    }

    // 🔴 THE CRITICAL FIX: Update state when a new product is selected in the modal
    #[On('loadProduct')]
    public function handleProductChanged($id)
    {
        $this->productId = (int) $id;
        $this->updateQuantityState();
    }

    #[Computed()]
    public function cart()
    {
        return Cart::with(['products' => function($query) {
            $query->withPivot('quantity');
        }])->valid()->first();
    }
    public function updateQuantityState()
    {
        unset($this->cart);

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
        // $cart = auth()->user()->cart ;
        $cart = Cart::with("products")->where("user_id" , auth()->id())->first();

        $cartProduct = $this->cart->products()->find($this->productId);
        

        if ($cartProduct) {
            $newQuantity = $cartProduct->pivot->quantity + 1;
            
            if ($newQuantity > 20) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "quantity" => ["You can't add more than 20 products"]
                ]);
            }

            $this->cart->products()->updateExistingPivot($this->productId, [
                'quantity' => $newQuantity,
            ]);
        } else {
            $this->cart->products()->attach($this->productId, ['quantity' => 1]);
        }

        $this->updateQuantityState();
        $this->dispatch("cart-updated");
    }

    public function decrement()
    {
        if (!$this->cart) return;

        $cartProduct = $this->cart->products()->find($this->productId);

        if (!$cartProduct) return;

        $newQuantity = $cartProduct->pivot->quantity - 1;

        if ($newQuantity <= 0) {
            $this->cart->products()->detach($this->productId);
        } else {
            $this->cart->products()->updateExistingPivot($this->productId, [
                'quantity' => $newQuantity,
            ]);
        }

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