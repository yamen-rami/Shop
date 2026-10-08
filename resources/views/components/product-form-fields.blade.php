@props(['product' => null, 'selectedTags' => []])

<section class="mb-6" aria-label="Product details">
    <h2 class="h5 mb-4">Product details</h2>
    <div class="product-form-grid">
        <x-form.input type="text" value="Name" feild="name" :edit="$product?->name" :stacked="true" />
        <x-form.input type="number" value="Quantity" feild="quantity" :edit="$product?->quantity" :stacked="true" />
        <x-form.input type="number" step="any" value="Price" feild="price" :edit="$product?->price" :stacked="true" />
        <x-form.input type="number" step="any" value="Int Price" feild="int_price" :edit="$product?->int_price" :stacked="true" />
        <div class="product-form-field">
            <label class="form-label" for="product-category">Category</label>
            <x-form.remote-select resource="categories" name="catagory_id" id="product-category"
                :selected="old('catagory_id', $product?->catagory_id)" placeholder="Search categories" />
            @error('catagory_id')<p class="text-danger mt-2 mb-0">{{ $message }}</p>@enderror
        </div>
        <div class="product-form-field">
            <label class="form-label" for="product-tags">Tags</label>
            <x-form.remote-select resource="tags" name="tags[]" id="product-tags" multiple
                :selected="old('tags', $selectedTags)" placeholder="Search tags" />
            @error('tags')<p class="text-danger mt-2 mb-0">{{ $message }}</p>@enderror
        </div>
        <x-form.textarea type="text" value="Description" feild="desc" :edit="$product?->desc" :stacked="true" rows="4" />
        <div class="product-form-field">
            <label class="form-label" for="product-featured">Featured</label>
            <label class="product-form-featured" for="product-featured">
                <input class="form-check-input m-0" type="checkbox" id="product-featured" name="featured" value="1"
                    @checked(old('featured', $product?->featured ?? false))>
                <span>Show this product on the homepage</span>
            </label>
            @error('featured')<p class="text-danger mt-2 mb-0">{{ $message }}</p>@enderror
        </div>
    </div>
</section>

@pushOnce('styles', 'product-form-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/product-form.css') }}">
@endPushOnce
