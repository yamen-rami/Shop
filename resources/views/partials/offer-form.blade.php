@php
    $editing = isset($offer);
    $offerType = old('type', $editing ? $offer->type : 'global');
    $discountType = old('discount_type', $editing ? $offer->discount_type : 'percentage');
    $discountValue = $editing ? ($offer->discount_type === 'percentage' ? $offer->discount_value * 100 : $offer->discount_value) : null;
@endphp
<form method="POST" action="{{ $editing ? route('offer.update', $offer) : route('offer.store') }}"
    x-data="{ type: @js($offerType), discountType: @js($discountType) }">
    @csrf
    @if($editing) @method('PATCH') @endif
    <x-form.input type="text" value="Name" feild="name" :edit="$editing ? $offer->name : null" />
    <div class="row mb-6">
        <label for="offer-type" class="col-sm-2 col-form-label">Offer type</label>
        <div class="col-sm-10">
            <select id="offer-type" class="form-select" name="type" x-model="type">
                @foreach(['global' => 'Global', 'coupon' => 'Coupon', 'categories' => 'Categories', 'products' => 'Products'] as $value => $label)
                    <option value="{{ $value }}" @selected($offerType === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('type')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>
    <div x-show="type === 'coupon'">
        <x-form.input type="text" value="Coupon code" feild="code" :edit="$editing ? $offer->code : null" x-bind:disabled="type !== 'coupon'" />
    </div>
    <div class="row mb-6" x-show="type === 'categories'">
        <label for="offer-categories" class="col-sm-2 col-form-label">Categories</label>
        <div class="col-sm-10">
            <x-form.remote-select id="offer-categories" resource="categories" name="categories[]" multiple :selected="old('categories', $selectedCategories ?? [])" x-bind:disabled="type !== 'categories'" placeholder="Search categories" />
            @error('categories')<p class="text-danger">{{ $message }}</p>@enderror
            @error('categories.*')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="row mb-6" x-show="type === 'products'">
        <label for="offer-products" class="col-sm-2 col-form-label">Products</label>
        <div class="col-sm-10">
            <x-form.remote-select id="offer-products" resource="products" name="products[]" multiple :selected="old('products', $selectedProducts ?? [])" x-bind:disabled="type !== 'products'" placeholder="Search products" />
            @error('products')<p class="text-danger">{{ $message }}</p>@enderror
            @error('products.*')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="row mb-6">
        <label for="discount-type" class="col-sm-2 col-form-label">Discount type</label>
        <div class="col-sm-10">
            <select id="discount-type" class="form-select" name="discount_type" x-model="discountType">
                <option value="percentage" @selected($discountType === 'percentage')>Percentage</option>
                <option value="fixed_amount" @selected($discountType === 'fixed_amount')>Fixed amount</option>
            </select>
            @error('discount_type')<p class="text-danger">{{ $message }}</p>@enderror
        </div>
    </div>
    <x-form.input type="number" value="Discount value" feild="discount_value" :edit="$discountValue" min="0.01" step="0.01" x-bind:max="discountType === 'percentage' ? 100 : null" />
    <x-form.input type="date" value="Start date" feild="start_date" :edit="$editing ? \Illuminate\Support\Carbon::parse($offer->start_date)->format('Y-m-d') : null" />
    <x-form.input type="date" value="End date" feild="end_date" :edit="$editing ? \Illuminate\Support\Carbon::parse($offer->end_date)->format('Y-m-d') : null" />
    <button type="submit" class="btn btn-primary">{{ $editing ? 'Update offer' : 'Create offer' }}</button>
</form>
