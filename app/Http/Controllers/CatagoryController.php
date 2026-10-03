<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{Catagory, Offer, Product};

class CatagoryController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('catagory.index');
    }
    public function edit(Catagory $catagory)
    {
        return view("catagory.edit", compact("catagory"));
    }
    public function show(Catagory $catagory)
    {
        $offer = $catagory->offer()->active()->where('type', 'categories')->latest('offers.id')->first();
        $products = $catagory->products()->with('tags')->orderByDesc('id')->paginate(10);
        return view("catagory.show", [
            "catagory" => $catagory,
            "products" => $products,
            "offer" => $offer , 
        ]);
    }
    public function create()
    {
        return view("catagory.create");
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            "name" => ["required", "string", "min:2"],
            "desc" => ["required", "string", "min:2"],
        ]);
        Catagory::create($validate);
        flash()->success("Catagory Has Been Created");
        return redirect()->route("catagory.index");
    }
    public function update(Catagory $catagory, Request $request)
    {
        $validate = $request->validate([
            'name' => ["required", "string"],
            "desc" => ["required", "string"],
        ]);
        $catagory->update($validate);
        flash()->info("Catagory Has Updated");
        return redirect()->route('catagory.index');
    }
    public function destroy(Catagory $catagory)
    {
        $catagory->delete();
        flash()->error("Catagory Has Delete in All Related Products");
        return redirect()->route("catagory.index");
    }
}
