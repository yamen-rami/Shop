<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\OfferService;
use App\Models\{Catagory, Favoriate, Offer, Product};

class HomeController extends Controller
{
    //
    public function home()
    {
        $products = Product::with("offers")->where("quantity", ">", 0)->where('featured', "on")
            ->simplePaginate(8);

        // 2. Pure memory extraction! Slice the first 3 items out for the slider (0 database queries!)
        $slider = $products->getCollection()->take(3);
        return view("home.home", ['products' => $products, "slider" => $slider ,  "offers" => Offer::with("products")->active()->get()]);
    }
   
    public  function products(Request $request)
    {
        $search = $request->search ?? "" ;
        $products = Product::with(["offers"])->where("name" , "LIKE" , "%" . $search ."%" )->where("quantity", ">", 1)->paginate(32);
        return view("home.products", ["products" => $products , "offers" => Offer::with(["products" , "categories"])->active()->get()]);
    }
    public function showProduct(Product $product)
    {
        return view('home.product', ["product" => $product]);
    }

    public function wishlist()
    {
        $favoraites = auth()->user()->favoriates()->with([
            "product.catagory",
            "product.tags",
            "product.companies"
        ])
            ->paginate(30);
        return view("home.wishlist", ["favoraites" => $favoraites]);
    }
    // public function categories(){
    //     $categories = Catagory::with("products")->paginate(15);
    //     return view("components.category",
    //      ["catagories" => $categories]
    //      );
    // }

    public function offers(Request $request)
    {
        $offers = Offer::with(["products", "categories"])->active()->whereNull("code")->paginate(10);
        return view("home.offers",  compact("offers"));
    }
    public function notFound(){
        return view("home.notfound");
    }
}
