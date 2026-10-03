<?php

namespace App\Livewire;

use App\Services\StorefrontData;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PublicOffers extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    #[Url]
    public string $search = '';
    #[Url]
    public string $type = '';
    #[Url]
    public string $sort = 'oldest';

    public function updated($property): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset('search', 'type', 'sort');
        $this->resetPage();
    }

    public function render()
    {
        $allOffers = app(StorefrontData::class)->offers();
        $offers = $allOffers->whereNull('code')->whereIn('type', ['global', 'products', 'categories']);
        if ($this->type !== '') {
            $offers = $offers->where('type', $this->type);
        }
        $search = trim($this->search);
        if ($search !== '') {
            $offers = $offers->filter(fn ($offer) => mb_stripos($offer->name, $search) !== false);
        }
        $offers = match ($this->sort) {
            'newest' => $offers->sortByDesc('id'), 'name' => $offers->sortBy('name'),
            default => $offers->sortBy('id'),
        };
        $page = $this->getPage();
        return view('livewire.public-offers', [
            'offers' => new LengthAwarePaginator($offers->forPage($page, 10)->values(), $offers->count(), 10, $page, ['path' => request()->url()]),
            'products_offers' => $allOffers,
        ]);
    }
}
