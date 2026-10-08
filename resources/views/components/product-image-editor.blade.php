@props(['product'])
<section class="product-image-editor mb-6" aria-label="Current product images">
    <h2 class="h5 mb-1">Current images <span class="badge bg-label-secondary">{{ $product->images->count() }}</span></h2>
    <p class="text-body-secondary mb-4">Update a photo or its color below. The first image is the main image shown across the shop.</p>
    @error('existing_images')<p class="text-danger" role="alert">{{ $message }}</p>@enderror
    <div class="product-image-grid">
        @forelse($product->images as $image)
            <div class="product-image-slot" x-data="{ preview: null, remove: {{ old('existing_images.'.$image->id.'.remove') ? 'true' : 'false' }}, destroy() { if (this.preview) URL.revokeObjectURL(this.preview) } }">
                <article class="border rounded p-3 h-100" x-bind:class="remove ? 'opacity-50' : ''">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="h6 mb-0">Image {{ $loop->iteration }}</h3>
                        @if($image->id === $product->image?->id)<span class="badge bg-label-primary">Main image</span>@endif
                    </div>
                    <div class="product-upload-preview rounded mb-3">
                        <x-record-image :src="$image->path" :alt="$product->name.' — '.($image->colors?->name ?? 'Photo')" x-show="!preview" />
                        <img x-show="preview" x-bind:src="preview" alt="Replacement photo preview" style="display: none">
                    </div>
                    <div class="product-image-fields">
                        <div>
                            <label for="replace-image-{{ $image->id }}" class="form-label">Replace photo</label>
                            <input type="file" class="form-control" id="replace-image-{{ $image->id }}"
                                name="existing_images[{{ $image->id }}][file]" accept="image/*" x-bind:disabled="remove"
                                x-on:change="if (preview) URL.revokeObjectURL(preview); preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                            @error('existing_images.'.$image->id.'.file')<p class="text-danger">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="existing-color-{{ $image->id }}" class="form-label">Color</label>
                            <x-form.remote-select resource="colors" id="existing-color-{{ $image->id }}"
                                name="existing_images[{{ $image->id }}][color_id]"
                                :selected="old('existing_images.'.$image->id.'.color_id', $image->color_id)" placeholder="Search colors" required />
                            @error('existing_images.'.$image->id.'.color_id')<p class="text-danger">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <label class="form-check d-flex align-items-center gap-2 mt-3 mb-0">
                        <input class="form-check-input mt-0" type="checkbox" name="existing_images[{{ $image->id }}][remove]" value="1" x-model="remove">
                        <span class="text-danger">Remove this image</span>
                    </label>
                </article>
            </div>
        @empty
            <p class="text-body-secondary">No images yet. Add your first photos below.</p>
        @endforelse
    </div>
</section>
<x-product-image-upload :adding="true" />
