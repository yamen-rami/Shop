@props(['src' => null, 'alt' => 'Image'])
<img src="{{ \App\Support\ImageUrl::resolve($src) }}" alt="{{ $alt }}"
    data-fallback-src="{{ \App\Support\ImageUrl::placeholder() }}"
    onerror="this.onerror = null; this.src = this.dataset.fallbackSrc;"
    {{ $attributes }}>
