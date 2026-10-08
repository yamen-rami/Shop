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
        $products = Product::with('offers', 'image')->where("quantity", ">", 0)->where('featured', true)
            ->simplePaginate(8);

        $slider = $products->take(3);
        return view("home.home", ['products' => $products, "slider" => $slider , "offers" => app(\App\Services\StorefrontData::class)->offers()]);
    }

    public function products(Request $request)
    {
        return view('home.products');
    }
    public function showProduct(Product $product)
    {
        $product->load('images.colors', 'tags', 'companies');
        $product->setRelation('image', $product->images->first());
        return view('home.product', ["product" => $product]);
    }

    public function wishlist()
    {
        return view('home.wishlist');
    }
    // public function categories(){
    //     $categories = Catagory::with("products")->paginate(15);
    //     return view("components.category",
    //      ["catagories" => $categories]
    //      );
    // }

    public function offers(Request $request)
    {
        return view('home.offers');
    }
    public function notFound(){
        return view("home.notfound");
    }
}
