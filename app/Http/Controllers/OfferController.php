<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;

use App\Models\{Catagory, Offer, Product};

class OfferController extends Controller
{
    // The Offer Controller Index 
    public function index(Request $request)
    {
        // dd(Offer::global()->first());

        $sort = $request->sort ?? "desc";
        $offers = Offer::with("categories")->global()->where("name", "LIKE", "%" . $request->search . "%")
            ->orderBy("id", $sort)
            ->paginate(30)->withQueryString();

        return view("offers.index", [
            "offers" => $offers,
            "sort" => $sort === "desc" ? $sort = "asc" : $sort = "desc",
        ]);
        // Get All Offers
    }
    public function productsOffer(Request $request)
    {
        $sort = $request->sort ?? "desc";
        $offers = Offer::with("products")
            ->where("name", "LIKE", "%" . $request->search . "%")
            ->orderBy("id", $sort)->typeProducts()->paginate(30);
        return view("offers.productsOffer", [
            "offers" => $offers,
            "sort" => $sort === "desc" ? $sort = "asc" : $sort = "desc"
        ]);
    }
    public function create()
    {
        $categories = Catagory::all();
        $products = Product::all();

        return view("offers.create", compact("categories", "products"));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => ["required", "min:2", "string"],
            "desc" => ["required", "min:2", "string"],
            "code" => ["nullable", "string"],
            "discount_type" => ["required"],
            "discount_value" => ["required", "integer"],
            "categories" => ["nullable", "array"],
            'categories*' => ['exists:catagories,id'],
            "start_date" => ["required", "date", "after_or_equal:today"],
            "end_date" => ["date", "after_or_equal:start_date"],
            "type" => ["required"],
            "products" => ['nullable', "array"],
            "products*" => ["exists:products,id"],
        ]);
        if ($data["start_date"] <= now () && now() <= $data["end_date"]) {
            $data["is_active"] = 1;
        } else {
            $data["is_active"] = 0;
        }
        if ($data["discount_type"] === "percentage") {
            $data["discount_value"] /= 100;
        }
        $offer = Offer::create(
            [
                "name" => $data["name"],
                "desc" => $data["desc"],
                "code" => $data["code"],
                "discount_type" => $data["discount_type"],
                "discount_value" => $data["discount_value"],
                "start_date" => $data["start_date"],
                "end_date" => $data["end_date"],
                "type" => $data["type"],
                "is_active" => $data["is_active"],
            ]
        );
        if ($request->categories) {
            foreach ($data["categories"] as $catagory) {
                $offer->categories()->attach($catagory);
            }
        }
        if ($request->products) {
            foreach ($data["products"] as $product) {
                // dd($data["products"]);
                $offer->products()->attach($product);
            }
        }
        flash()->success("Offer Has Created");
        if ($data['type'] === "categories") {
            return redirect()->route("catagoryOffers");
        } elseif ($offer->type === "coupon") {
            return redirect()->route("offerCoupons");
        } elseif($offer->type === "products") {
           return redirect()->route("productsOffer");
        }else{
            return redirect()->route("offer.index");
        }
    }
    public function edit(Offer $offer)
    {
        $categories = Catagory::all();
        $products = Product::all();

        return view("offers.edit", compact("offer", "categories", "products"));
    }
    public function show() {}
    public function update(Request $request, Offer $offer)
    {
        $data = $request->validate([
            "name" => ["required", "min:2", "string"],
            "desc" => ["nullable", "min:2", "string"],
            "code" => ["nullable", "string"],
            "discount_type" => ["required"],
            "discount_value" => ["required", "integer"],
            "categories" => ["nullable", "array"],
            'categories.*' => ['exists:catagories,id'],
            "start_date" => ["required", "date"],
            "end_date" => ["date", "after_or_equal:start_date"],
            "type" => ["required"],
            "products" => ['nullable', "array"],
            "products.*" => ["exists:products,id"],
        ]);

        if (date("Y-m-d") === $data["start_date"]) {
            $data["is_active"] = true;
        }
        if ($data["discount_type"] === "percentage") {
            $data["discount_value"] /= 100;
        }
        if ($request->categories) {
            foreach ($data["categories"] as $catagory) {
                $offer->categories()->sync($catagory);
            }
        }
        if ($request->products) {
            foreach ($data["products"] as $product) {
                // dd($data["products"]);
                $offer->products()->sync($product);
            }
        }
        $offer->update(Arr::except($data, ["categories", "products"]));
        flash()->info("Offer Updated Successfully");
        if ($data['type'] === "categories") {
            return redirect()->route("catagoryOffers");
        } elseif ($offer->type === "coupon") {
            return redirect()->route("offerCoupons");
        } else {
            return redirect()->route("offer.index");
        }
    }
    public function destroy(Offer $offer)
    {
        $offer->delete();
        flash()->error("Offer Has Deletd All Products Prices Will Back To Orignal Price");
        return redirect()->back();
    }
}
