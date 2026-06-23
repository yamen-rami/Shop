<?php

use Livewire\Component;
use App\Models\{Product, Cart};
new class extends Component {
    //
    public ?Product $product = null;
    public function addCart(Product $product)
    {
        if (!auth()->check()) {
            return redirect()->route("login");
        }
        $cart = Cart::firstOrCreate([
            "user_id" => auth()->id(),
        ]);
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
        $this->dispatch("cart-updated");

        // Tell every page to rerender itslef
    }
};
?>

<div class="ec-quickview-cart ">
    <button class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close"
        wire:click='addCart({{ $this->product->id }})'>
        <i class="fi-rr-shopping-basket"></i>Add To Cart</button>
</div>