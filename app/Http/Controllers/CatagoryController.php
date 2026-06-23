<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{Catagory, Offer, Product};

class CatagoryController extends Controller
{
    //
    public function index(Request $request)
    {
        $sort = $request->sort ?? "desc";
        $catagores  = Catagory::where("name", "LIKE", "%" . $request->search . "%")
            ->orWhere("desc", "LIKE", "%" . $request->search . "%")->orderBy('id', $sort)->paginate(30);
        return view("catagory.index", [
            "catagores" => $catagores,
            "sort" => $sort === "desc" ? $sort = "asc" : $sort = "desc",
        ]);
    }
    public function edit(Catagory $catagory)
    {
        return view("catagory.edit", compact("catagory"));
    }
    public function show(Catagory $catagory)
    {
        $offer = Offer::active()->where("catagory_id" , $catagory->id)->latest()->first();
        $products = Product::where("catagory_id", $catagory->id)->paginate(30);
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
