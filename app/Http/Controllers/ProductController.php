<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Product;
use App\Http\Requests\{StoreProductRequest, UpdateProductRequest};

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort;
        // ? Get all the products 
        $products = Product::query()
        ->where("name", 'LIKE', "%" . $request->search . "%")->orWhere("desc" , "LIKE" , "%" . $request->search ."%")
        ->orWhere("quantity" , "LIKE" , "%" . $request->search ."%")
        ->orWhere('price' , "LIKE" , "%" . $request->search ."%")
        ->orWhere("int_price" ,"LIKE" , "%" . $request->search ."%")
        ->orderBy("id", $request->sort ?? "desc")
        ->paginate(30)->withQueryString();
        return view("products.index", [
            "products" => $products , 
            "sort" => $sort === "desc" ? $sort = "asc" : $sort = "desc", 
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("products/create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        //
        $validatedData = $request->validated();
        // dd($validatedData);
        $image = $validatedData["image"] ?? null;
        $validatedData["image"] = $image->store('products', "public");
        Product::create($validatedData);
        return redirect()->route("product.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
           return view("products.show" , compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        return view('products.edit', compact("product"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
        $validateData = $request->validated();
        if ($product->image) {
            Storage::disk("public")->delete($product->image);

            $path = $validateData["image"]->store('products', 'public');
        }
        $validateData["image"] = $path;

        $product->update($validateData);
        return redirect()->route("product.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // dd($product);

        $product->delete();
        Storage::disk("public")->delete($product->image);
        return redirect()->back();
    }
}
