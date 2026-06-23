<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;

use App\Models\{Catagory, Product, Tag};
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
        $products = Product::query()->with(["companies", "tags", "catagory"])
            ->where("name", 'LIKE', "%" . $request->search . "%")->orWhere("desc", "LIKE", "%" . $request->search . "%")
            ->orWhere("quantity", "LIKE", "%" . $request->search . "%")
            ->orWhere('price', "LIKE", "%" . $request->search . "%")
            ->orWhere("int_price", "LIKE", "%" . $request->search . "%")
            ->orderBy("id", $request->sort ?? "desc")
            ->paginate(30)->withQueryString();
        $catagories = Catagory::all();
        return view("products.index", [
            "products" => $products,
            "catagories" => $catagories,
            "sort" => $sort === "desc" ? $sort = "asc" : $sort = "desc",
        ]);
    }
    public function catagoryProducts(Catagory $catagory)
    {
        $products = Product::with(['companies', "tags", "catagory"])
            ->where("catagory_id", $catagory->id)->paginate(30)->withQueryString();

        flash()->success("Products Has Filtered");
        $catagories = Catagory::all();
        return view("products.index", [
            "products" => $products,
            "catagories" => $catagories,
            "sort" =>  "desc"
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get The Tags
        $tags = Tag::all();
        $catagories = Catagory::all();
        return view("products/create", compact("tags", "catagories"));
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
        if (empty($validatedData["tags"])) {
            $validatedData["tags"] = [];
        }
        if ($validatedData["tags"]) {
            $tags = $validatedData["tags"];
            foreach ($tags as $tag) {
                $product->tag($tag);
            }
        }
        flash()->success('Product created successfully!');
        return redirect()->route("product.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // $products = Product::with("tags")->where("id" , $product->id)
        $tags = Tag::all();
        $catagories = Catagory::all();

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
        //
        $tags = Tag::all();
        $catagories = Catagory::all();

        return view('products.edit', compact("product", "tags", "catagories"));
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
        
        $P = $product->update(Arr::except($validateData, "tags"));
        $tags = $validateData["tags"] ?? [];
        if (empty($tags)) {
            $validateData["tags"] = [];
        }
        if ($validateData["tags"]) {
            if ($product->tags->count() > 0) {
                $product->tags()->detach();
            }
            foreach ($tags as $tag) {
                $product->tags()->attach($tag);
            }
        }
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
        $product->tags()->detach($product->id);
        Storage::disk("public")->delete($product->image);
        flash()->error('Product Deleted Succesfully!');

        return redirect()->back();
    }
}
