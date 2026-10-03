<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;

use App\Models\{Catagory, Offer, Product, Tag};
use App\Http\Requests\{StoreProductRequest, UpdateProductRequest};

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('products.index');
    }
    public function catagoryProducts(Catagory $catagory)
    {
        return redirect()->route('product.index', ['category' => $catagory->id]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();

        if ($validatedData["price"] <= $validatedData["int_price"]) {
            throw ValidationException::withMessages([
                'int_price' => ["The Int Price Must Be Lower Than Price"]
            ]);
        }


        $image = $validatedData["image"] ?? null;
        $validatedData["original_price"] = $validatedData["price"];
        $validatedData["image"] = $image->store('products', "public");
        $product = Product::create(Arr::except($validatedData, "tags"));
        // Attach Tag To Product
        $product->tags()->sync($validatedData['tags'] ?? []);
        flash()->success('Product created successfully!');
        return redirect()->route("product.index");
    }

    // ? first return false or on 
    // ? get 
    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // $products = Product::with("tags")->where("id" , $product->id)
        $product->load('catagory', 'tags', 'companies');
        $tags = collect();
        $catagories = collect();
        return view("products.show", [
            "product" => $product,
            "tags" => $tags,
            "catagories" => $catagories
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', ['product' => $product, 'selectedTags' => $product->tags()->pluck('tags.id')->toArray()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
        $validateData = $request->validated();
        if ($request->hasFile("image")) {
            if ($product->image) {
                Storage::disk("public")->delete($product->image);
            }
            $path = $validateData["image"]->store('products', 'public');
            $validateData["image"] = $path;
        }
        if (!$request->hasFile('image')) { unset($validateData['image']); }
        $validateData['original_price'] = $validateData['price'];
        $product->update(Arr::except($validateData, "tags"));
        $product->tags()->sync($validateData['tags'] ?? []);
        flash()->info('Product Updated successfully!');

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
        flash()->error('Product Deleted Succesfully!');

        return redirect()->back();
    }
}
