<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreOrderRequest, UpdateOrderRequest};
use App\Models\{Order, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return view('orders.index');
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data) {
            $product = Product::whereKey($data['product_id'])->lockForUpdate()->firstOrFail();
            $this->checkStock($product, (int) $data['quantity']);
            $order = Order::create([
                ...Arr::except($data, ['product_id', 'price']),
                'price' => round($product->price * $data['quantity'], 2),
            ]);
            $order->user()->attach(auth()->id());
            $order->products()->attach($product->id);
            $product->decrement('quantity', $data['quantity']);
        });

        flash()->success('Order created successfully.');
        return redirect()->route('order.index');
    }

    public function show(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load('products', 'user');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $this->authorizeOrder($order);
        return view('orders.edit', ['order' => $order, 'selectedProduct' => $order->products()->value('products.id')]);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $this->authorizeOrder($order);
        $data = $request->validated();
        DB::transaction(function () use ($order, $data) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $oldIds = $order->products()->pluck('products.id');
            $products = Product::whereIn('id', $oldIds->push((int) $data['product_id'])->unique())
                ->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            foreach ($order->products as $previous) {
                $products[$previous->id]->quantity += (int) $order->quantity;
            }
            $product = $products->get((int) $data['product_id']);
            abort_unless($product, 404);
            $this->checkStock($product, (int) $data['quantity']);
            $product->quantity -= (int) $data['quantity'];
            foreach ($products as $changed) {
                $changed->save();
            }
            $order->update([
                ...Arr::except($data, ['product_id', 'price']),
                'price' => round($product->price * $data['quantity'], 2),
            ]);
            $order->products()->sync([$product->id]);
        });

        flash()->success('Order updated successfully.');
        return redirect()->route('order.index');
    }

    public function destroy(Order $order)
    {
        $this->authorizeOrder($order);
        DB::transaction(function () use ($order) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $products = $order->products()->orderBy('products.id')->lockForUpdate()->get();
            foreach ($products as $product) {
                $product->increment('quantity', (int) $order->quantity);
            }
            $order->delete();
        });

        flash()->success('Order deleted and stock restored.');
        return redirect()->route('order.index');
    }

    private function authorizeOrder(Order $order): void
    {
        abort_unless(auth()->user()->role === 'admin' || $order->user()->whereKey(auth()->id())->exists(), 403);
    }

    private function checkStock(Product $product, int $quantity): void
    {
        if ($quantity > $product->quantity) {
            throw ValidationException::withMessages(['quantity' => "Only {$product->quantity} units of {$product->name} are available."]);
        }
    }
}
