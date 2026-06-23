<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{Catagory, Favoriate, Product};

class HomeController extends Controller
{
    //
    public function home()
    {
        $products = Product::with(["companies" , "tags" , "catagory"])
        ->where("quantity", ">", 0)
        ->paginate(8);
        $slider = Product::with(["tags", "catagory", "companies"])->where("quantity", ">", 1)->paginate(3);
        return view("home.home" , ['products' => $products , "slider" => $slider]);
    }
    public  function products(Request $request)
    {
        $products = Product::with(["tags" , "companies" , "catagory"])->where("quantity" , ">" ,1)->latest()->paginate(30);
        return view("home.products" , ["products" => $products]);   
    }
    public function showProduct(Product $product){
        return view('home.product' , ["product" => $product]);
    }

    public function wishlist(){
        $favoraites = auth()->user()->favoriates()->with("product")->paginate(30);
        return view("home.wishlist" , ["favoraites" => $favoraites]);
    }
    // public function categories(){
    //     $categories = Catagory::with("products")->paginate(15);
    //     return view("components.category",
    //      ["catagories" => $categories]
    //      );
    // }
    
    
}
