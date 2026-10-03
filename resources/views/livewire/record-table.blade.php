<div class="card" x-data>
    <div class="card-header d-flex flex-wrap gap-3 align-items-center">
        <label class="flex-grow-1">Search
            <input type="search" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Search {{ str_replace('-', ' ', $resource) }}">
        </label>
        <label>Sort by
            <select class="form-select" wire:model.live="sortBy">
                @foreach($sortable as $column)<option value="{{ $column }}">{{ ucwords(str_replace('_', ' ', $column)) }}</option>@endforeach
            </select>
        </label>
        <label>Direction
            <select class="form-select" wire:model.live="sort"><option value="desc">Descending</option><option value="asc">Ascending</option></select>
        </label>
        @if($resource === 'products')
            <label wire:ignore>Category
            <x-form.remote-select resource="categories" field="categoryId" :selected="$categoryId" placeholder="All categories" />
        </label>
        <label wire:ignore>Company
            <x-form.remote-select resource="companies" field="companyId" :selected="$companyId" placeholder="All companies" />
        </label>
            <label>Featured
                <select class="form-select" wire:model.live="featured"><option value="">All products</option><option value="1">Featured</option><option value="0">Regular</option></select>
            </label>
        @endif
        @if($isOffer || $resource === 'products')
            <label>Status
                <select class="form-select" wire:model.live="status">
                    <option value="">All statuses</option>
                    @if($isOffer)<option value="active">Active</option><option value="inactive">Inactive</option>
                    @else<option value="in-stock">In stock</option><option value="out-of-stock">Out of stock</option>@endif
                </select>
            </label>
        @endif
        <button type="button" class="btn btn-outline-secondary align-self-end" wire:click="clearFilters">Clear</button>
        <a class="btn btn-primary align-self-end" href="{{ route($routePrefix . '.create') }}">Create</a>
    </div>
    <div wire:loading.delay class="px-6 pb-3" role="status">Updating results…</div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr>
                <th>ID</th><th>{{ $resource === 'contacts' ? 'Title' : 'Name' }}</th>
                @if($resource === 'products')
                    <th>Category</th><th>Image</th><th>Description</th><th>Featured</th><th>Quantity</th><th>Cost</th><th>Price</th><th>Store price</th><th>Offer</th><th>Companies</th><th>Tags</th>
                @elseif($isOffer)
                    <th>Code</th><th>Discount</th><th>Start</th><th>End</th><th>Status</th><th>Applies to</th>
                @elseif($resource === 'orders')
                    <th>Products</th><th>Customer</th><th>Location</th><th>Quantity</th><th>Total</th>
                @elseif($resource === 'contacts')
                    <th>Email</th><th>Message</th><th>Customer</th>
                @elseif($resource === 'companies')
                    <th>Description</th><th>Products</th>
                @endif
                <th>Actions</th>
            </tr></thead>
            <tbody>
                @forelse($records as $record)
                    <tr wire:key="{{ $resource }}-{{ $record->id }}">
                        <td>{{ $record->id }}</td><td>{{ $record->name ?? $record->title }}</td>
                        @if($resource === 'products')
                            @php($discount = app(\App\Services\OfferService::class)->getDiscount($record, $offers))
                            <td>{{ $record->catagory?->name ?? '—' }}</td>
                            <td><img src="{{ str_starts_with($record->image ?? '', 'assets/') ? asset($record->image) : asset('storage/' . $record->image) }}" alt="{{ $record->name }}" width="40" height="40" class="rounded object-fit-cover"></td>
                            <td>{{ Str::limit($record->desc, 60) }}</td><td>{{ $record->featured ? 'Yes' : 'No' }}</td><td>{{ $record->quantity }}</td>
                            <td>{{ $record->int_price }}</td><td>{{ $record->price }}</td><td>{{ $discount['best'] }}</td>
                            <td>{{ app(\App\Services\OfferService::class)->offerType($discount['offer']) }}</td>
                            <td>{{ $record->companies->pluck('name')->join(', ') ?: '—' }}</td><td>{{ $record->tags->pluck('name')->join(', ') ?: '—' }}</td>
                        @elseif($isOffer)
                            <td>{{ $record->code ?? '—' }}</td><td>{{ $record->discount_type === 'percentage' ? ($record->discount_value * 100) . '%' : $record->discount_value }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($record->start_date)->format('Y-m-d') }}</td><td>{{ \Illuminate\Support\Carbon::parse($record->end_date)->format('Y-m-d') }}</td>
                            <td>{{ $record->is_active && now()->between($record->start_date, $record->end_date) ? 'Active' : 'Inactive' }}</td>
                            <td>{{ ($record->type === 'categories' ? $record->categories : $record->products)->pluck('name')->join(', ') ?: '—' }}</td>
                        @elseif($resource === 'orders')
                            <td>{{ $record->products->pluck('name')->join(', ') }}</td><td>{{ $record->user->pluck('name')->join(', ') }}</td>
                            <td>{{ $record->location }}</td><td>{{ $record->quantity }}</td><td>{{ number_format($record->price, 2) }}</td>
                        @elseif($resource === 'contacts')
                            <td>{{ $record->email }}</td><td>{{ Str::limit($record->desc, 80) }}</td><td>{{ $record->user?->name ?? '—' }}</td>
                        @elseif($resource === 'companies')
                            <td>{{ Str::limit($record->desc, 80) }}</td><td>{{ $record->products->pluck('name')->join(', ') }}</td>
                        @endif
                        <td>
                            <div class="d-flex gap-2">
                                @if($resource !== 'tags')<a class="btn btn-sm btn-outline-primary" href="{{ route($routePrefix . '.show', $record) }}">Show</a>@endif
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route($routePrefix . '.edit', $record) }}">Edit</a>
                                <form method="POST" action="{{ route($routePrefix . '.destroy', $record) }}" x-on:submit="if (!confirm('Delete this record?')) $event.preventDefault()">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="15" class="text-center py-6">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-3">{{ $records->links() }}</div>
</div>
