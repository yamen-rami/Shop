<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

use App\Models\{Catagory, Offer, Product, Tag};
use App\Http\Requests\{StoreProductRequest, UpdateProductRequest};
use Illuminate\Support\Facades\DB;

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
        $data = $request->validated();
        $paths = [];

        try {
            DB::transaction(function () use ($data, &$paths) {
                $product = Product::create([
                    ...Arr::except($data, ['tags', 'images', 'image_count']),
                    'original_price' => $data['price'],
                ]);

                foreach ($data['images'] as $image) {
                    $path = $image['file']->store('products', 'public');
                    $paths[] = $path;
                    $product->images()->create([
                        'path' => $path,
                        'color_id' => $image['color_id'],
                        'product_id' => $product->id,
                    ]);
                }

                $product->tags()->sync($data['tags'] ?? []);
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($paths);
            throw $exception;
        }
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
        $product->load('catagory', 'tags', 'companies', 'image', 'images.colors');
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
        $product->load('image.colors', 'images.colors');
        return view('products.edit', ['product' => $product, 'selectedTags' => $product->tags()->pluck('tags.id')->toArray()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $newPaths = [];
        $oldPaths = [];

        try {
            DB::transaction(function () use ($product, $data, &$newPaths, &$oldPaths) {
                foreach ($data['existing_images'] ?? [] as $id => $changes) {
                    $image = $product->images()->whereKey($id)->firstOrFail();
                    if ($changes['remove'] ?? false) {
                        $oldPaths[] = $image->path;
                        $image->delete();
                        continue;
                    }

                    $attributes = ['color_id' => $changes['color_id']];
                    if (isset($changes['file'])) {
                        $attributes['path'] = $changes['file']->store('products', 'public');
                        $newPaths[] = $attributes['path'];
                        $oldPaths[] = $image->path;
                    }
                    $image->update($attributes);
                }

                foreach ($data['images'] ?? [] as $image) {
                    $path = $image['file']->store('products', 'public');
                    $newPaths[] = $path;
                    $product->images()->create(['path' => $path, 'color_id' => $image['color_id'], 'product_id' => $product->id]);
                }

                if (isset($data['image'])) {
                    $path = $data['image']->store('products', 'public');
                    $newPaths[] = $path;
                    $image = $product->image()->first();
                    if ($image) {
                        $oldPaths[] = $image->path;
                    }
                    $attributes = ['path' => $path, 'color_id' => $data['color_id'], 'product_id' => $product->id];
                    $image ? $image->update($attributes) : $product->images()->create($attributes);
                } elseif (isset($data['color_id'])) {
                    $product->image()->first()?->update(['color_id' => $data['color_id']]);
                }

                $product->update([
                    ...Arr::except($data, ['tags', 'image', 'color_id', 'images', 'existing_images', 'image_count']),
                    'original_price' => $data['price'],
                ]);
                $product->tags()->sync($data['tags'] ?? []);
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($newPaths);
            throw $exception;
        }

        Storage::disk('public')->delete($oldPaths);
        flash()->info('Product Updated successfully!');

        return redirect()->route("product.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $paths = $product->images()->pluck('path')->all();
        DB::transaction(function () use ($product) {
            $product->images()->delete();
            $product->delete();
        });
        Storage::disk('public')->delete($paths);
        flash()->error('Product Deleted Succesfully!');

        return redirect()->back();
    }
}
