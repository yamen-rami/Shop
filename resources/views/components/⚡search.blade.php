<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Product;
new class extends Component {
    //
    use WithPagination;
    public string $search = "";
    #[Computed()]
    public function products()
    {
        if (trim($this->search) === '') {
            return collect(); // Returns an empty collection
        }
        return Product::where("quantity", ">", 1)
            ->where("name", "LIKE", "%" . $this->search . "%")->limit(10)->get();
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
};
?>

<div>

    {{-- Well begun is half done. - Aristotle --}}
    <div class="align-self-center">
        <div class="header-search">
            <input class="form-control ec-search-bar" wire:model.live.debounce.300ms='search'
                placeholder="{{ __("home.search") }}" type="text">
            <button  class="submit" type="submit"><i class="fi-rr-search"></i></button>
        </div>
        <div style="position: absolute; z-index: 1000; height: fit;" class="bg-dark header-search text-light">
                @if ($this->products->isNotEmpty())
                    @forelse($this->products as $product)
                        <p class="f-bold pl-4 pt-30 text-start">
                            <a class="text-light"  wire:navigate href="{{ route("showProduct", $product->id) }}">{{ $product->name }}</a>
                        </p>
                        <hr class="text-light">
                    @empty
                        <p>
                            There Is No Products Found
                        </p>
                    @endforelse
                @endif
            </div>
    </div>

</div>