<div class="col-12">
    <div class="d-flex flex-wrap gap-3 mb-5 align-items-end">
        <label class="flex-grow-1">Search
            <input class="form-control" type="search" wire:model.live.debounce.300ms="search" placeholder="Name or description">
        </label>
        <label wire:ignore>Category
            <x-form.remote-select resource="categories" field="categoryId" :selected="$categoryId" placeholder="All categories" />
        </label>
        <label wire:ignore>Company
            <x-form.remote-select resource="companies" field="companyId" :selected="$companyId" placeholder="All companies" />
        </label>
        <label>Sort
            <select class="form-select" wire:model.live="sort">
                <option value="oldest">Oldest first</option><option value="newest">Newest first</option>
                <option value="name">Name</option><option value="price-asc">Price: low to high</option><option value="price-desc">Price: high to low</option>
            </select>
        </label>
        <button type="button" class="btn btn-primary" wire:click="clearFilters">Clear</button>
    </div>
    <div wire:loading.delay role="status">Updating products…</div>
    @if($products->isEmpty())
        <p class="text-center py-5">{{ $wishlist ? 'Your wishlist is empty.' : 'No products found.' }}</p>
    @else
        <livewire:cards :products="$products->getCollection()" :products_offer="$offers" :wishlist="$wishlist"
            :key="'catalog-cards-' . ($wishlist ? 'wishlist' : 'products') . '-' . md5($products->pluck('id')->join(','))" />
    @endif
    <div class="mt-4">{{ $products->links() }}</div>
</div>
