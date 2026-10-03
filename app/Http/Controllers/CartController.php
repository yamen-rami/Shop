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
    public function show(){
        return view("home.checkout" , ['products' => Product::with("offers")->latest()->limit(4)->get()]);
    }
    public function delete(int $productId){
        $globalCart = app(\App\Services\StorefrontData::class)->cart();
        if (!$globalCart) {
            return redirect()->route('home');
        }
        $globalCart->products()->detach($productId);
        flash()->success("product has deleted succefully");
        return redirect()->route("checkout");
    }
}
