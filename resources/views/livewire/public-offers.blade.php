<div class="col-12">

    @forelse($offers as $offer)
        <div class="mb-5" wire:key="public-offer-{{ $offer->id }}">
            <h6 class="text-center fw-bold ec-bg-title">{{ $offer->name }}</h6>
            <p class="text-center">
                <strong>{{ $offer->discount_type === 'percentage' ? $offer->discount_value * 100 . '%' : '$' . $offer->discount_value }}</strong>
                {{ $offer->type === 'global' ? 'For all products' : 'For selected ' . $offer->type }}</p>
            @if ($offer->type === 'products')
                <livewire:cards :products="$offer->products" :products_offer="$products_offers" :key="'offer-cards-' . $offer->id" />
            @elseif($offer->type === 'categories')
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    @foreach ($offer->categories as $category)
                        <a href="{{ route('products', ['category' => $category->id]) }}"
                            class="btn btn-primary">{{ $category->name }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <p class="text-center py-5">No Available Offers</p>
    @endforelse
    {{ $offers->links() }}
</div>
