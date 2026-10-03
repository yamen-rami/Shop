<?php

namespace App\Livewire;

use App\Models\{Catagory, Product};
use App\Services\StorefrontData;
use Livewire\Attributes\{Locked, On, Url};
use Livewire\Component;
use Livewire\WithPagination;

class Catalog extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    #[Locked]
    public bool $wishlist = false;
    #[Url]
    public string $search = '';
    #[Url(as: 'category')]
    public string $categoryId = '';
    #[Url(as: 'company')]
    public string $companyId = '';
    #[Url]
    public string $sort = 'oldest';

    public function mount(bool $wishlist = false): void
    {
        $this->wishlist = $wishlist;
    }

    public function updated($property): void
    {
        $this->resetPage();
    }

    #[On('wishlist-updated')]
    public function refreshWishlist(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset('search', 'categoryId', 'companyId', 'sort');
        $this->resetPage();
    }

    public function render()
    {
        abort_if($this->wishlist && !auth()->check(), 403);
        $query = Product::query();
        if ($this->wishlist) {
            $query->whereHas('favoriate', fn ($query) => $query->where('user_id', auth()->id()));
        } else {
            $query->where('quantity', '>', 0);
        }
        $search = trim($this->search);
        if ($search !== '') {
            $query->where(fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere('desc', 'like', "%{$search}%"));
        }
        if ($this->categoryId !== '') {
            $query->where('catagory_id', (int) $this->categoryId);
        }
        if ($this->companyId !== '') {
            $query->whereHas('companies', fn ($query) => $query->whereKey((int) $this->companyId));
        }
        [$column, $direction] = match ($this->sort) {
            'newest' => ['id', 'desc'], 'name' => ['name', 'asc'],
            'price-asc' => ['price', 'asc'], 'price-desc' => ['price', 'desc'],
            default => ['id', 'asc'],
        };
        return view('livewire.catalog', [
            'products' => $query->orderBy($column, $direction)->when($column !== 'id', fn ($query) => $query->orderBy('id'))->paginate($this->wishlist ? 30 : 32),
            'offers' => app(StorefrontData::class)->offers(),
        ]);
    }
}
