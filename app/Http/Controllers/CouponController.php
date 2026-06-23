<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Offer;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->sort ?? "desc";

        $couponOffers = Offer::coupons()
            ->where("name", "LIKE", '%' . $request->search . '%')
            ->orWhere("code", "LIKE", '%' . $request->search . "%")
            ->orderBy("id", $sort)
            ->paginate(30)->withQueryString();
        return view("coupons.index", [
            "offers" => $couponOffers,
            "sort" => $sort === "desc" ? $sort = "asc" : $sort = "desc"
        ]);
    }
    public function catagory(Request $request)
    {
        $sort = $request->sort ?? "desc";
        $couponOffers = Offer::with("catagory")->whereNotNull("catagory_id")
            ->where("name", "LIKE", '%' . $request->search . '%')
            ->orderBy("id", $sort)
            ->paginate(30)->withQueryString();
        return view("catagory_offer.index", [
            "offers" => $couponOffers,
            "sort" => $sort === "desc" ? $sort = "asc" : $sort = "desc"
        ]);
    }
    //
}
