@props(['adding' => false])
@php($imageCount = max($adding ? 0 : 1, min(12, (int) old('image_count', $adding ? 0 : 1))))
<section class="product-upload mb-6" x-data="{ count: {{ $imageCount }} }" aria-label="Product images">
    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h5 mb-1">{{ $adding ? 'Add more images' : 'Product images' }}</h2>
            <p class="text-body-secondary mb-0">{{ $adding ? 'New photos are added after your current images. A product can have up to 12 images in total.' : 'Choose 1 to 12 images. Image 1 is the main image shown across the shop.' }}</p>
        </div>
        <a href="{{ route('color.create') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">Create a color</a>
    </div>
    <div class="mb-4" style="max-width: 220px">
        <label for="image-count" class="form-label">{{ $adding ? 'How many new images?' : 'How many images?' }}</label>
        <select id="image-count" name="image_count" class="form-select" x-model.number="count">
            @if($adding)<option value="0" @selected($imageCount === 0)>No new images</option>@endif
            @foreach(range(1, 12) as $number)
                <option value="{{ $number }}" @selected($imageCount === $number)>{{ $number }} {{ $number === 1 ? 'image' : 'images' }}</option>
            @endforeach
        </select>
        @error('image_count')<p class="text-danger mt-2">{{ $message }}</p>@enderror
    </div>
    @error('images')<p class="text-danger">{{ $message }}</p>@enderror
    <div class="product-image-grid">
        @foreach(range(0, 11) as $index)
            <div class="product-image-slot" x-show="count > {{ $index }}"
                @if($index >= $imageCount) style="display: none" @endif
                x-data="{ preview: null, destroy() { if (this.preview) URL.revokeObjectURL(this.preview) } }">
                <article class="border rounded p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="h6 mb-0">Image {{ $index + 1 }}</h3>
                        @if($index === 0 && !$adding)<span class="badge bg-label-primary">Main image</span>@endif
                    </div>
                    <div class="product-upload-preview rounded mb-3">
                        <img x-show="preview" x-bind:src="preview" alt="Preview of image {{ $index + 1 }}" style="display: none">
                        <div x-show="!preview" class="text-body-secondary text-center p-3">
                            <i class="icon-base ti tabler-photo mb-2" style="font-size: 2rem"></i>
                            <div>Choose a product photo</div>
                        </div>
                    </div>
                    <div class="product-image-fields">
                        <div>
                            <label for="product-image-{{ $index }}" class="form-label">Photo</label>
                            <input type="file" id="product-image-{{ $index }}" name="images[{{ $index }}][file]"
                                class="form-control" accept="image/*"
                                x-bind:disabled="count <= {{ $index }}" x-bind:required="count > {{ $index }}"
                                x-on:change="if (preview) URL.revokeObjectURL(preview); preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                            @error('images.'.$index.'.file')<p class="text-danger">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="product-image-color-{{ $index }}" class="form-label">Color</label>
                            <x-form.remote-select resource="colors" id="product-image-color-{{ $index }}"
                                name="images[{{ $index }}][color_id]" :selected="old('images.'.$index.'.color_id')"
                                placeholder="Search colors" x-bind:disabled="count <= {{ $index }}"
                                x-bind:required="count > {{ $index }}" />
                            @error('images.'.$index.'.color_id')<p class="text-danger">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </article>
            </div>
        @endforeach
    </div>
    <small class="text-body-secondary d-block mt-3">Each image can be up to 5 MB. After a validation error, choose your photo files again.</small>
</section>
@include('partials.select2-assets')
