<?php

use App\Models\Catagory;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: 'desc')]
    public string $sort = 'desc';

    #[Url(as: 'sort_by', except: 'id')]
    public string $sortBy = 'id';

    public function boot(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
    }

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'sort', 'sortBy'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset('search', 'sort', 'sortBy');
        $this->resetPage();
    }

    public function render()
    {
        $search = trim($this->search);
        $sortBy = in_array($this->sortBy, ['id', 'name', 'created_at'], true) ? $this->sortBy : 'id';
        $sort = in_array($this->sort, ['asc', 'desc'], true) ? $this->sort : 'desc';
        $catagores = Catagory::query()
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('desc', 'like', "%{$search}%");
            }))
            ->orderBy($sortBy, $sort)
            ->when($sortBy !== 'id', fn ($query) => $query->orderBy('id', $sort))
            ->paginate(30);

        return $this->view(compact('catagores'));
    }
};
?>

<div class="card" x-data="{ deleteUrl: '', deleteName: '' }">
    @php($deleteModalId = 'delete-category-' . $this->getId())
    <div class="card-header d-flex flex-wrap align-items-end gap-3">
        <div class="flex-grow-1">
            <label for="category-search" class="form-label">Search categories</label>
            <input id="category-search" type="search" class="form-control"
                wire:model.live.debounce.300ms="search" placeholder="Name or description">
        </div>
        <div>
            <label for="category-sort-by" class="form-label">Sort by</label>
            <select id="category-sort-by" class="form-select" wire:model.live="sortBy">
                <option value="id">ID</option>
                <option value="name">Name</option>
                <option value="created_at">Created date</option>
            </select>
        </div>
        <div>
            <label for="category-sort" class="form-label">Direction</label>
            <select id="category-sort" class="form-select" wire:model.live="sort">
                <option value="desc">Descending</option>
                <option value="asc">Ascending</option>
            </select>
        </div>
        <button type="button" class="btn btn-outline-secondary" wire:click="clearFilters">Clear</button>
        <a class="btn btn-primary" href="{{ route('catagory.create') }}">Create category</a>
    </div>
    <div class="px-4" wire:loading.delay role="status">Updating categories…</div>
    <div class="table-responsive" wire:loading.class="opacity-50">
        <table class="table">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Description</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($catagores as $catagory)
                    <tr wire:key="category-{{ $catagory->id }}">
                        <td>{{ $catagory->id }}</td>
                        <td>{{ $catagory->name }}</td>
                        <td>{{ Str::limit($catagory->desc, 100) }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('catagory.show', $catagory) }}">Show</a>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('catagory.edit', $catagory) }}">Edit</a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal" data-bs-target="#{{ $deleteModalId }}"
                                    data-delete-url="{{ route('catagory.destroy', $catagory) }}"
                                    data-delete-name="{{ $catagory->name }} (#{{ $catagory->id }})"
                                    x-on:click="deleteUrl = $el.dataset.deleteUrl; deleteName = $el.dataset.deleteName">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-4">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $catagores->links() }}</div>
    <div class="modal fade" id="{{ $deleteModalId }}" tabindex="-1"
        aria-labelledby="{{ $deleteModalId }}-title" aria-describedby="{{ $deleteModalId }}-description"
        aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $deleteModalId }}-title">Confirm deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="{{ $deleteModalId }}-description">
                    <p>Are you sure you want to delete <strong x-text="deleteName"></strong>?</p>
                    <p class="mb-0 text-body-secondary">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" x-bind:action="deleteUrl"
                        x-on:submit="if (!deleteUrl) $event.preventDefault()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" x-bind:disabled="!deleteUrl">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
