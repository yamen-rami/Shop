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

        $slider = $products->take(3);
        return view("home.home", ['products' => $products, "slider" => $slider , "offers" => app(\App\Services\StorefrontData::class)->offers()]);
    }

    public  function products(Request $request)
    {
        $search = $request->search ?? "" ;
        $products = Product::with(["offers"])->where("name" , "LIKE" , "%" . $search ."%" )->where("quantity", ">", 1)->paginate(32);
        return view("home.products", ["products" => $products , "offers" => app(\App\Services\StorefrontData::class)->offers()]);
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
        $products_offers = app(\App\Services\StorefrontData::class)->offers();
        $publicOffers = $products_offers->whereNull('code')->values();
        $page = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $offers = new \Illuminate\Pagination\LengthAwarePaginator(
            $publicOffers->forPage($page, 10)->values(),
            $publicOffers->count(),
            10,
            $page,
            ['path' => $request->url(), 'query' => $request->query()],
        );

        return view('home.offers', compact('offers', 'products_offers'));
    }
    public function notFound(){
        return view("home.notfound");
    }
}
