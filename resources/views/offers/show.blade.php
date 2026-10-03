@extends('admin')
@section('title', $offer->name)
@section('header', $offer->name)
@section('content')
    <div class="card"><div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Type</dt><dd class="col-sm-9">{{ ucfirst($offer->type) }}</dd>
            <dt class="col-sm-3">Code</dt><dd class="col-sm-9">{{ $offer->code ?? '—' }}</dd>
            <dt class="col-sm-3">Discount</dt><dd class="col-sm-9">{{ $offer->discount_type === 'percentage' ? ($offer->discount_value * 100) . '%' : $offer->discount_value }}</dd>
            <dt class="col-sm-3">Dates</dt><dd class="col-sm-9">{{ $offer->start_date }} — {{ $offer->end_date }}</dd>
            <dt class="col-sm-3">Categories</dt><dd class="col-sm-9">{{ $offer->categories->pluck('name')->join(', ') ?: '—' }}</dd>
            <dt class="col-sm-3">Products</dt><dd class="col-sm-9">{{ $offer->products->pluck('name')->join(', ') ?: '—' }}</dd>
        </dl>
        <a class="btn btn-primary" href="{{ route('offer.edit', $offer) }}">Edit offer</a>
    </div></div>
@endsection
