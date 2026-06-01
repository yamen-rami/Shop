<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{Cart, Product};

class CartController extends Controller
{
    // add To Cart 
    public function addCart(Product $product)
    {
        $products = Product::all();
        $cart = Cart::firstOrCreate([
            "user_id" => auth()->id(),
        ]);
        $exsistingProduct = $cart->product()->where("product_id" , $product->id)->first();
        
        if($exsistingProduct){
            $cart->product()->updateExistingPivot($product->id  ,[
                "quantity" => $exsistingProduct->pivot->quantity + 1 ,
            ]); 
        }else{
            $cart->product()->attach($product->id , [
                "quantity" => 1
            ]);
        }
        return redirect()->back();
    }

    public function show()
    {
        $cart = auth()->user()->cart()->with("product")->get();
        return view("home.checkout", [
            "cart" => $cart,
        ]);
    }

    public function destroy(Cart $cart){
        $cart->delete();
        return redirect()->back();
    }
}
