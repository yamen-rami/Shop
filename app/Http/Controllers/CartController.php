<?php
// ! FINSHED
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Carbon\Carbon as CarbonAlias;

use App\Models\{Cart, Offer, Product};

class CartController extends Controller
{
    public function destroy(Product $product)
    {
        return $this->delete($product->id);
    }

    public function addCart(Product $product)
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        if ($cart->created_at->lte(now()->subDay())) {
            $cart->forceFill(['created_at' => now()])->save();
        }
        $existing = $cart->products()->whereKey($product->id)->first();
        $quantity = ($existing?->pivot->quantity ?? 0) + 1;
        if ($quantity > $product->quantity) {
            throw \Illuminate\Validation\ValidationException::withMessages(['quantity' => 'Insufficient stock.']);
        }
        $cart->products()->syncWithoutDetaching([$product->id => ['quantity' => $quantity]]);
        app(\App\Services\StorefrontData::class)->forgetCart();
        return redirect()->back();
    }

    // add To Cart 
    public function show(){
        return view("home.checkout" , ['products' => Product::with('offers', 'image')->latest()->limit(4)->get()]);
    }
    public function delete(int $productId){
        $globalCart = app(\App\Services\StorefrontData::class)->cart();
        if (!$globalCart) {
            return redirect()->route('home');
        }
        $globalCart->products()->detach($productId);
        app(\App\Services\StorefrontData::class)->forgetCart();
        flash()->success("product has deleted succefully");
        return redirect()->route("checkout");
    }
}
