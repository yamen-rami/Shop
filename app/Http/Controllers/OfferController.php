<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Models\{Catagory, Offer};

class OfferController extends Controller
{
    // The Offer Controller Index 
    public function index(Request $request)
    {
        // dd(Offer::global()->first());

        $sort = $request->sort ?? "desc";   
        $offers = Offer::with("catagory")->global()->where("name", "LIKE", "%" . $request->search . "%")
            ->orderBy("id", $sort)
            ->paginate(30)->withQueryString();
     
        return view("offers.index", [
            "offers" => $offers,
            "sort" => $sort === "desc" ? $sort = "asc" : $sort = "desc",
        ]);
        // Get All Offers
    }

    public function create()
    {
        $catagories = Catagory::all();
        return view("offers.create", compact("catagories"));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => ['required', "string", "min:3"],
            "code" => ['nullable', "string", "min:3"],
            "catagory_id" => ['nullable', "exists:catagories,id"],
            "discount_value" => ["required", "integer"],
            "discount_type" => ['required'],
            "start_date" => ['required', "date", "after_or_equal:today"],
            "end_date" => ['required', "date", "after:start_date"],
        ]);
        if (date("Y-m-d") === $data["start_date"]) {
            $data["is_active"] = true;
        }
        if ($data['discount_type'] === 'percentage') {
            $data['discount_value'] = $data['discount_value'] / 100; // Turns 10 into 0.10
        }
        $offer = Offer::create($data);
        $is = '';
        if ($offer->is_active) {
            $is = "Active";
        } else {
            $is = "Not Active";
        }
        flash()->success("Offer Created Succefully and it's $is ");
        if ($offer->code) {
            return redirect()->route("offerCoupons");
        }
        return redirect()->route("offer.index");
    }
    public function edit(Offer $offer)
    {
        $catagories = Catagory::all();
        return view("offers.edit", compact("offer","catagories"));
    }
    public function show() {}
    public function update(Request $request, Offer $offer)
    {
        $data = $request->validate([
            "name" => ['required', "string", "min:3"],
            "code" => ['nullable', "string", "min:3"],
            "discount_value" => ["required", "integer"],
            "discount_type" => ['required'],
            "start_date" => ['required', "date", "after_or_equal:today"],
            "end_date" => ['required', "date", "after:start_date"],
        ]);
        if (date("Y-m-d") === $data["start_date"]) {
            $data["is_active"] = true;
        }
        if ($data["discount_type"] === "percentage") {
            $data["discount_value"] /= 100;
        }

        $offer->update($data);
        flash()->info("Offer Updated Successfully");
        return redirect()->route("offer.index");
    }
    public function destroy(Offer $offer)
    {
        $offer->delete();
        flash()->error("Offer Has Deletd All Products Prices Will Back To Orignal Price");
        return redirect()->back();
    }
}
