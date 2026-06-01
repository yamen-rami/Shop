<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Offer;

class OfferController extends Controller
{
    //
    public function index(Request $request)
    {
        $sort = $request->sort ?? "desc";
        $offers = Offer::where("name", "LIKE", "%" . $request->search . "%")
        ->paginate(10)->withQueryString();
        return view("offers.index", [
            "offers" => $offers,
            "sort" => $sort === "desc" ? $sort = "asc" : $sort = "desc",
        ]);
    }
    public function create() {}
    public function store() {}
    public function edit() {}
    public function show() {}
    public function update() {}
    public function destory() {}
}
