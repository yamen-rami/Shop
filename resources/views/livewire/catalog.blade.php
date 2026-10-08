<div class="col-12 catalog" x-data="catalogSidebar()" x-bind:class="{ 'catalog--collapsed': collapsed }">
    <div class="catalog-toolbar">
        <button type="button" class="catalog-toggle" x-on:click="toggle()" aria-controls="catalog-sidebar"
            x-bind:aria-expanded="!collapsed" aria-expanded="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                <path d="M4 7h16M4 17h16M8 4v6M16 14v6" />
            </svg>
            <span x-text="collapsed ? 'Show filters' : 'Hide filters'">Hide filters</span>
        </button>
        <span class="catalog-count" role="status">{{ $products->total() }} {{ $products->total() === 1 ? 'product' : 'products' }}</span>
        <label class="catalog-sort" for="catalog-sort">Sort by
            <select id="catalog-sort" class="form-select" wire:model.live="sort">
                <option value="oldest">Oldest first</option><option value="newest">Newest first</option>
                <option value="name">Name</option><option value="price-asc">Price: low to high</option><option value="price-desc">Price: high to low</option>
            </select>
        </label>
    </div>
    <div class="catalog-layout">
        <aside id="catalog-sidebar" class="catalog-sidebar" x-show="!collapsed" aria-label="Product filters">
            <div class="catalog-sidebar-heading">
                <h3>Filters</h3>
                <button type="button" class="catalog-clear" wire:click="clearFilters">Reset all</button>
            </div>
            <div class="catalog-filter-section">
                <label for="catalog-search">Search products</label>
                <input id="catalog-search" class="form-control" type="search" wire:model.live.debounce.300ms="search" placeholder="Name or description">
            </div>
            <div class="catalog-filter-section">
                <label for="catalog-category">Category</label>
                <div wire:ignore>
                    <x-form.remote-select id="catalog-category" resource="categories" field="categoryId" :selected="$categoryId" placeholder="All categories" />
                </div>
            </div>
            <div class="catalog-filter-section">
                <label for="catalog-company">Company</label>
                <div wire:ignore>
                    <x-form.remote-select id="catalog-company" resource="companies" field="companyId" :selected="$companyId" placeholder="All companies" />
                </div>
            </div>
            <div class="catalog-filter-section catalog-price" x-data="catalogPriceFilter($wire)" x-ref="priceBounds"
                data-minimum="{{ $priceFloor }}" data-maximum="{{ $priceCeiling }}">
                <h4>Price range</h4>
                <p class="catalog-filter-help">Find a price that suits you.</p>
                <div class="catalog-price-slider" dir="ltr">
                    <div class="catalog-price-track"><div class="catalog-price-selection" x-bind:style="selectionStyle"></div></div>
                    <input class="catalog-range catalog-range-min" type="range" min="{{ $priceFloor }}" max="{{ $priceCeiling }}" step="0.01"
                        value="{{ is_numeric($minPrice) ? $minPrice : $priceFloor }}" x-bind:value="lower"
                        x-on:input="setMinimum($event.target.value)" x-on:change="apply()" aria-label="Minimum price">
                    <input class="catalog-range catalog-range-max" type="range" min="{{ $priceFloor }}" max="{{ $priceCeiling }}" step="0.01"
                        value="{{ is_numeric($maxPrice) ? $maxPrice : $priceCeiling }}" x-bind:value="upper"
                        x-on:input="setMaximum($event.target.value)" x-on:change="apply()" aria-label="Maximum price">
                </div>
                <div class="catalog-price-fields">
                    <label for="catalog-min-price">Min price
                        <span class="catalog-price-input"><span aria-hidden="true">$</span>
                            <input id="catalog-min-price" type="number" min="{{ $priceFloor }}" max="{{ $priceCeiling }}" step="0.01"
                                value="{{ is_numeric($minPrice) ? $minPrice : $priceFloor }}" x-bind:value="lower"
                                x-on:change="setMinimum($event.target.value); apply()">
                        </span>
                    </label>
                    <span class="catalog-price-separator" aria-hidden="true">&ndash;</span>
                    <label for="catalog-max-price">Max price
                        <span class="catalog-price-input"><span aria-hidden="true">$</span>
                            <input id="catalog-max-price" type="number" min="{{ $priceFloor }}" max="{{ $priceCeiling }}" step="0.01"
                                value="{{ is_numeric($maxPrice) ? $maxPrice : $priceCeiling }}" x-bind:value="upper"
                                x-on:change="setMaximum($event.target.value); apply()">
                        </span>
                    </label>
                </div>
            </div>
            <div class="catalog-filter-section">
                <div class="catalog-color-heading">
                    <h4>Colors</h4>
                    @if($colorId !== '')
                        <button type="button" class="catalog-clear" wire:click="selectColor('')">Clear</button>
                    @endif
                </div>
                <p class="catalog-filter-help">Choose a product photo color.</p>
                @php
                    $swatches = [
                        'black' => '#242424', 'white' => '#ffffff', 'grey' => '#969696', 'gray' => '#969696',
                        'red' => '#d94a4a', 'blue' => '#4a7fd4', 'navy' => '#263c64', 'green' => '#4a8062',
                        'sage' => '#a5b69e', 'mint' => '#b5dfcd', 'yellow' => '#f1d36b', 'pink' => '#e7afbd',
                        'cream' => '#f3ead5', 'brown' => '#8b6250', 'orange' => '#e89451', 'purple' => '#9473ba',
                        'multicolor' => 'conic-gradient(#d94a4a, #f1d36b, #4a8062, #4a7fd4, #9473ba, #d94a4a)',
                    ];
                @endphp
                <div class="catalog-colors" role="group" aria-label="Filter by image color">
                    @forelse($colors as $color)
                        @php
                            $colorName = strtolower(trim($color->name));
                            $swatch = $swatches[$colorName] ?? (preg_match('/\A(?:[a-z]+|#[0-9a-f]{3,8})\z/i', $colorName) ? $colorName : '#d6d1c8');
                        @endphp
                        <button type="button" class="catalog-color {{ $colorId === (string) $color->id ? 'is-selected' : '' }}"
                            style="--swatch: {{ $swatch }}" wire:click="selectColor('{{ $color->id }}')" wire:key="catalog-color-{{ $color->id }}"
                            title="{{ $color->name }}" aria-label="{{ $color->name }}" aria-pressed="{{ $colorId === (string) $color->id ? 'true' : 'false' }}">
                            <span class="catalog-color-dot" aria-hidden="true"></span>
                            <span class="catalog-color-name">{{ $color->name }}</span>
                        </button>
                    @empty
                        <p class="catalog-filter-help">No colors available yet.</p>
                    @endforelse
                </div>
            </div>
        </aside>
        <div class="catalog-results">
            <div class="catalog-loading" wire:loading.delay role="status">Updating products&hellip;</div>
            @if($products->isEmpty())
                <div class="catalog-empty">
                    <p>{{ $wishlist ? 'Your wishlist is empty or no products match your filters.' : 'No products found. Try adjusting your filters.' }}</p>
                    <button type="button" class="btn btn-primary" wire:click="clearFilters">Reset filters</button>
                </div>
            @else
                <livewire:cards :products="$products->getCollection()" :products_offer="$offers" :wishlist="$wishlist"
                    :key="'catalog-cards-' . ($wishlist ? 'wishlist' : 'products') . '-' . md5($products->pluck('id')->join(','))" />
            @endif
            <div class="mt-4">{{ $products->links() }}</div>
        </div>
    </div>
</div>
@pushOnce('styles', 'catalog-filter-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/catalog-filters.css') }}">
@endPushOnce
@pushOnce('scripts', 'catalog-filter-scripts')
    <script src="{{ asset('assets/js/catalog-filters.js') }}"></script>
@endPushOnce
