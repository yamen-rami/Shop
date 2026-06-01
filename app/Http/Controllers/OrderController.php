<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

use App\Models\{Order, Product, User};
use App\Http\Requests\{StoreOrderRequest, UpdateOrderRequest};

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? "desc";
        $orders = Order::with(["products"])
            ->where("name", "LIKE", "%" . $request->search . "%")
            ->orWhere("qunatity", $request->search)->orderBy("id", $sort)->paginate(30);
        return view(
            "orders.index",
            [
                "orders" => $orders,
                "sort" => $sort === "desc" ? $sort = "asc" : $sort = "desc",
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $products = Product::all();
        return view("orders.create", [
            "products" => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        // get the validated data
        $data = $request->validated();
        // creating order except the product_id cause the belongsToMany pivot table
        $order = Order::create(Arr::except($data, "product_id"));
        $product = Product::findOrFail($data["product_id"]);
        if ($data["quantity"] > $product->quantity) {
            throw ValidationException::withMessages([
                "quantity" => ["Sorry there is only $product->quantity for $product->name "],
            ]);
        } else {

            $quantity = $product->quantity - $order->quantity;
            $product->update([
                "quantity" => $quantity,
            ]);
            // define the auth user
            $user = auth()->user();
            // attach it in the pivot table
            $order->user()->attach($user->id);
            // attach the product table 
            $order->products()->attach($data["product_id"]);

            // todo flash messaging success 
            return redirect()->route("order.index");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // todo flash messaging
        return view("orders.show", compact("order"));
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        // sending data into edit
        $products = Product::all();
        return view("orders.edit", ["order" => $order, "products" => $products]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $data = $request->validated();
        $order->update(Arr::except($data, "product_id"));
        $order->user()->sync(auth()->user()->id);
        $order->products()->sync($data["product_id"]);
        // todo flash messaging 
        return redirect()->route("order.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
        $order->delete();
        // todo flash message
        return redirect()->route("order.index");
    }
}
