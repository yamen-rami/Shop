<?php

namespace App\Livewire;

use App\Models\Color;
use App\Models\Product;
use App\Services\StorefrontData;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
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

    #[Url(as: 'min-price')]
    public string $minPrice = '';

    #[Url(as: 'max-price')]
    public string $maxPrice = '';

    #[Url(as: 'color')]
    public string $colorId = '';

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
        $this->reset('search', 'categoryId', 'companyId', 'sort', 'minPrice', 'maxPrice', 'colorId');
        $this->resetPage();
    }

    public function selectColor(string $id): void
    {
        $this->colorId = $this->colorId === $id ? '' : $id;
        $this->resetPage();
    }

    public function render()
    {
        abort_if($this->wishlist && ! auth()->check(), 403);
        $query = Product::with('image');
        if ($this->wishlist) {
            $query->whereHas('favoriate', fn ($query) => $query->where('user_id', auth()->id()));
        } else {
            $query->where('quantity', '>', 0);
        }
        $priceBounds = (clone $query)->toBase()
            ->selectRaw('MIN(price) as minimum, MAX(price) as maximum')->first();
        $priceFloor = floor((float) ($priceBounds->minimum ?? 0));
        $priceCeiling = max($priceFloor + 1, ceil((float) ($priceBounds->maximum ?? 0)));
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
        if ($this->colorId !== '') {
            $query->whereHas('images', fn ($query) => $query->where('color_id', (int) $this->colorId));
        }
        $minimum = is_numeric($this->minPrice) ? max(0, (float) $this->minPrice) : null;
        $maximum = is_numeric($this->maxPrice) ? max(0, (float) $this->maxPrice) : null;
        if ($minimum !== null && $maximum !== null && $minimum > $maximum) {
            [$minimum, $maximum] = [$maximum, $minimum];
        }
        if ($minimum !== null) {
            $query->where('price', '>=', $minimum);
        }
        if ($maximum !== null) {
            $query->where('price', '<=', $maximum);
        }
        [$column, $direction] = match ($this->sort) {
            'newest' => ['id', 'desc'], 'name' => ['name', 'asc'],
            'price-asc' => ['price', 'asc'], 'price-desc' => ['price', 'desc'],
            default => ['id', 'asc'],
        };

        return view('livewire.catalog', [
            'products' => $query->orderBy($column, $direction)->when($column !== 'id', fn ($query) => $query->orderBy('id'))->paginate($this->wishlist ? 30 : 32),
            'offers' => app(StorefrontData::class)->offers(),
            'colors' => Color::orderBy('name')->get(['id', 'name']),
            'priceFloor' => $priceFloor,
            'priceCeiling' => $priceCeiling,
        ]);
    }
}
