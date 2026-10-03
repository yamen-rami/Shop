@php
    $editing = isset($order);
    $selectedProduct = old('product_id', $selectedProduct ?? ($editing && $order->relationLoaded('products') ? $order->products->first()?->id : ''));
@endphp
<form method="POST" action="{{ $editing ? route('order.update', $order) : route('order.store') }}"
    x-data="{ product: @js((string) $selectedProduct), quantity: @js(old('quantity', $editing ? $order->quantity : 1)), unitPrice: 0 }" x-on:remote-select-changed="unitPrice = Number($event.detail.selected[0]?.price || 0)">
    @csrf
    @if($editing) @method('PATCH') @endif
    <x-form.input type="text" value="Name" feild="name" :edit="$editing ? $order->name : null" />
    <x-form.textarea value="Location" feild="location" :edit="$editing ? $order->location : null" />
    <x-form.input type="number" value="Quantity" feild="quantity" :edit="$editing ? $order->quantity : 1" min="1" step="1" x-model.number="quantity" />
    <div class="row mb-6">
        <label for="order-product" class="col-sm-2 col-form-label">Product</label>
        <div class="col-sm-10">
            <x-form.remote-select id="order-product" resource="products" name="product_id" :selected="$selectedProduct" x-model="product" placeholder="Search products" />
            @error('product_id')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="row mb-6">
        <label for="order-total" class="col-sm-2 col-form-label">Total</label>
        <div class="col-sm-10">
            <input id="order-total" class="form-control" readonly :value="(Number(unitPrice) * Math.max(0, Number(quantity || 0))).toFixed(2)">
            <small>The total is calculated from the product price when saved.</small>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ $editing ? 'Update order' : 'Create order' }}</button>
</form>
