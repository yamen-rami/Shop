@props(['product'])
@php
    $product->loadMissing('image', 'images.colors');
    $photos = $product->images;
    $items = $photos->map(fn ($image) => [
        'src' => \App\Support\ImageUrl::resolve($image->path),
        'color' => $image->colors?->name ?? 'Product photo',
    ])->values();
@endphp
<section class="product-gallery" aria-label="{{ $product->name }} image gallery"
    x-data="{ active: 0, items: {{ \Illuminate\Support\Js::from($items) }}, move(delta) { this.active = (this.active + delta + this.items.length) % this.items.length } }"
    x-on:keydown.right.prevent="if (items.length > 1) move(1)"
    x-on:keydown.left.prevent="if (items.length > 1) move(-1)">
    <div class="product-gallery-stage">
        @forelse($photos as $image)
            <figure class="product-gallery-photo" x-show="active === {{ $loop->index }}"
                @unless($loop->first) style="display: none" @endunless>
                <button type="button" class="product-gallery-enlarge" x-on:click="$refs.lightbox.showModal()"
                    aria-label="Enlarge {{ $product->name }}, {{ $image->colors?->name ?? 'image '.($loop->index + 1) }}">
                    <x-record-image :src="$image->path" :alt="$product->name.' — '.($image->colors?->name ?? 'Photo').' — image '.$loop->iteration"
                        :loading="$loop->first ? 'eager' : 'lazy'" />
                    <span class="product-gallery-zoom">&#x2922; View larger</span>
                </button>
                <figcaption class="product-gallery-caption">
                    <span>{{ $image->colors?->name ?? 'Product photo' }}</span>
                    <span>{{ $loop->iteration }} / {{ $photos->count() }}</span>
                </figcaption>
            </figure>
        @empty
            <figure class="product-gallery-photo">
                <x-record-image :src="$product->image?->path" :alt="$product->name" />
                <figcaption class="product-gallery-caption">No photos yet</figcaption>
            </figure>
        @endforelse
        @if($photos->count() > 1)
            <button type="button" class="product-gallery-arrow product-gallery-prev" x-on:click="move(-1)" aria-label="Previous image">&#8249;</button>
            <button type="button" class="product-gallery-arrow product-gallery-next" x-on:click="move(1)" aria-label="Next image">&#8250;</button>
        @endif
    </div>
    @if($photos->isNotEmpty())
        <div class="product-gallery-thumbnails" aria-label="Choose a product image">
            @foreach($photos as $image)
                <button type="button" class="product-gallery-thumbnail" x-on:click="active = {{ $loop->index }}"
                    x-bind:aria-pressed="active === {{ $loop->index }}" @if($loop->first) aria-pressed="true" @else aria-pressed="false" @endif
                    aria-label="Show image {{ $loop->iteration }}, {{ $image->colors?->name ?? 'Product photo' }}">
                    <x-record-image :src="$image->path" :alt="$product->name.' image '.$loop->iteration" loading="lazy" />
                    <span>{{ $image->colors?->name ?? 'Photo '.$loop->iteration }}</span>
                    @if($loop->first)<small>Main</small>@endif
                </button>
            @endforeach
        </div>
        <p class="product-gallery-help">Choose a photo to explore. Select the large image for a closer look.</p>
        <dialog class="product-gallery-lightbox" x-ref="lightbox" aria-label="{{ $product->name }} enlarged image"
            x-on:click="if ($event.target === $el) $el.close()">
            <div class="product-gallery-lightbox-content">
                <button type="button" class="product-gallery-close" x-on:click="$refs.lightbox.close()" aria-label="Close image">&#215;</button>
                <x-record-image :src="$product->image?->path" :alt="$product->name" x-bind:src="items[active]?.src"
                    x-bind:alt="{{ \Illuminate\Support\Js::from($product->name.' — ') }} + items[active]?.color" />
                <div class="product-gallery-lightbox-controls">
                    @if($photos->count() > 1)
                        <button type="button" x-on:click="move(-1)" aria-label="Previous enlarged image">&#8249;</button>
                    @endif
                    <span aria-live="polite" x-text="items[active]?.color + ' · ' + (active + 1) + ' / ' + items.length"></span>
                    @if($photos->count() > 1)
                        <button type="button" x-on:click="move(1)" aria-label="Next enlarged image">&#8250;</button>
                    @endif
                </div>
            </div>
        </dialog>
    @endif
</section>
@pushOnce('styles', 'product-gallery-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/product-images.css') }}">
@endPushOnce
