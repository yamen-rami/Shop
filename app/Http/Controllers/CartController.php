<?php
// ! FINSHED
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Carbon\Carbon as CarbonAlias;

use App\Models\{Cart, Offer, Product};

class CartController extends Controller
{
    // add To Cart 
    public function addCart(Product $product)
    {
        $products = Product::all();
        $cart = Cart::firstOrCreate([
            "user_id" => auth()->id(),
        ]);
        // TODO Flash Messages
        // filtering cart and then delete the cart who have been created since 1 day of course in the model 

        // cart 
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
        return redirect()->back();
    }
    public function show()
    {
        $cart = auth()->user()->cart()->with(["products.companies"])
        ->where('created_at', '>=', now()->subDay())->first();
        $offer = Offer::where("is_active" , true)->first();
        return view("home.checkout", [
            "cart" => $cart,
            "offer" => $offer ,
        ]);
         
    }
    public function getOffer(Request $request)
    {
        if ($request->code) {
            $cart = auth()->user()->cart;
            $offer = Offer::where("code", $request->code)->get();
            return view("home.checkout", compact(["offer", "cart"]));
        }
    }
    public function destroy(Product $product)
    {
        $cart = auth()->user()->cart;
        $cart->products()->detach($product->id);
        return redirect()->back();
    }
}
