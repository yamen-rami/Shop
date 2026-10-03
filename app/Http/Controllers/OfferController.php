<?php

namespace App\Http\Controllers;

use App\Models\{Catagory, Offer, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OfferController extends Controller
{
    public function index()
    {
        return view('offers.index');
    }

    public function productsOffer()
    {
        return view('offers.productsOffer');
    }

    public function create()
    {
        return view('offers.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedOffer($request);
        $offer = DB::transaction(function () use ($data) {
            $offer = Offer::create(Arr::except($data, ['categories', 'products']));
            $this->syncTargets($offer, $data);
            return $offer;
        });
        flash()->success('Offer created successfully.');
        return redirect()->route($this->listingRoute($offer));
    }

    public function edit(Offer $offer)
    {
        return view('offers.edit', ['offer' => $offer,
            'selectedCategories' => $offer->categories()->pluck('catagories.id')->toArray(),
            'selectedProducts' => $offer->products()->pluck('products.id')->toArray(),
        ]);
    }

    public function show(Offer $offer)
    {
        $offer->load('categories', 'products');
        return view('offers.show', compact('offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        $data = $this->validatedOffer($request, $offer);
        DB::transaction(function () use ($offer, $data) {
            $offer->update(Arr::except($data, ['categories', 'products']));
            $this->syncTargets($offer, $data);
        });
        flash()->success('Offer updated successfully.');
        return redirect()->route($this->listingRoute($offer));
    }

    public function destroy(Offer $offer)
    {
        $route = $this->listingRoute($offer);
        $offer->delete();
        flash()->success('Offer deleted successfully.');
        return redirect()->route($route);
    }

    private function validatedOffer(Request $request, ?Offer $offer = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'type' => ['required', Rule::in(['global', 'coupon', 'categories', 'products'])],
            'code' => ['nullable', 'required_if:type,coupon', 'string', 'max:255', Rule::unique('offers', 'code')->ignore($offer?->id)],
            'discount_type' => ['required', Rule::in(['percentage', 'fixed_amount'])],
            'discount_value' => ['required', 'numeric', 'gt:0', $request->discount_type === 'percentage' ? 'max:100' : 'max:999999'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'categories' => ['nullable', 'required_if:type,categories', 'array', 'min:1'],
            'categories.*' => ['required', 'integer', 'distinct', 'exists:catagories,id'],
            'products' => ['nullable', 'required_if:type,products', 'array', 'min:1'],
            'products.*' => ['required', 'integer', 'distinct', 'exists:products,id'],
        ]);
        $data['code'] = $data['type'] === 'coupon' ? ($data['code'] ?? null) : null;
        $data['is_active'] = true;
        if ($data['discount_type'] === 'percentage') {
            $data['discount_value'] /= 100;
        }
        $data['end_date'] = \Illuminate\Support\Carbon::parse($data['end_date'])->endOfDay();
        return $data;
    }

    private function syncTargets(Offer $offer, array $data): void
    {
        $offer->categories()->sync($data['type'] === 'categories' ? ($data['categories'] ?? []) : []);
        $offer->products()->sync($data['type'] === 'products' ? ($data['products'] ?? []) : []);
    }

    private function listingRoute(Offer $offer): string
    {
        return match ($offer->type) {
            'categories' => 'catagoryOffers', 'coupon' => 'offerCoupons',
            'products' => 'productsOffer', default => 'offer.index',
        };
    }
}
