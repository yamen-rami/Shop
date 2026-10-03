<?php

namespace App\Services;

use App\Models\{Cart, Catagory, Offer};
use Illuminate\Database\Eloquent\Collection;

class StorefrontData
{
    private bool $cartLoaded = false;
    private ?Cart $cart = null;
    private ?Collection $offers = null;
    private ?Collection $categories = null;
    private ?int $favoriatesCount = null;

    public function cart(): ?Cart
    {
        if (! $this->cartLoaded) {
            $this->cart = auth()->check()
                ? Cart::with('products')->valid()->first()
                : null;
            $this->cartLoaded = true;
        }

        return $this->cart;
    }

    public function cartCount(): int
    {
        return (int) ($this->cart()?->products->sum('pivot.quantity') ?? 0);
    }

    public function forgetCart(): void
    {
        $this->cartLoaded = false;
        $this->cart = null;
    }

    public function offers(): Collection
    {
        return $this->offers ??= Offer::with(['products', 'categories'])->active()->get();
    }


    public function favoriates(): int
    {
        return $this->favoriatesCount ??= auth()->check()
            ? auth()->user()->favoriates()->count()
            : 0;
    }

    public function forgetFavoriates(): void
    {
        $this->favoriatesCount = null;
    }

    public function categories(): Collection
    {
        return $this->categories ??= Catagory::with('products')->limit(10)->get();
    }
}
