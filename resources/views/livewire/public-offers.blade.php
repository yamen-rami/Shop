<div class="col-12">
    <div class="d-flex flex-wrap gap-3 mb-5 align-items-end">
        <label class="flex-grow-1">Search offers<input type="search" class="form-control" wire:model.live.debounce.300ms="search"></label>
        <label>Type<select class="form-select" wire:model.live="type"><option value="">All offers</option><option value="global">Global</option><option value="products">Products</option><option value="categories">Categories</option></select></label>
        <label>Sort<select class="form-select" wire:model.live="sort"><option value="oldest">Oldest first</option><option value="newest">Newest first</option><option value="name">Name</option></select></label>
        <button type="button" class="btn btn-primary" wire:click="clearFilters">Clear</button>
    </div>
    <div wire:loading.delay role="status">Updating offers…</div>
    @forelse($offers as $offer)
        <div class="mb-5" wire:key="public-offer-{{ $offer->id }}">
            <h6 class="text-center fw-bold ec-bg-title">{{ $offer->name }}</h6>
            <p class="text-center"><strong>{{ $offer->discount_type === 'percentage' ? ($offer->discount_value * 100) . '%' : '$' . $offer->discount_value }}</strong>
                {{ $offer->type === 'global' ? 'For all products' : 'For selected ' . $offer->type }}</p>
            @if($offer->type === 'products')
                <livewire:cards :products="$offer->products" :products_offer="$products_offers" :key="'offer-cards-'.$offer->id" />
            @elseif($offer->type === 'categories')
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    @foreach($offer->categories as $category)<a href="{{ route('products', ['category' => $category->id]) }}" class="btn btn-primary">{{ $category->name }}</a>@endforeach
                </div>
            @endif
        </div>
    @empty
        <p class="text-center py-5">No Available Offers</p>
    @endforelse
    {{ $offers->links() }}
</div>
