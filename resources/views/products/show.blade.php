@section("title")
  Showing Product {{ $product->name }}
@endsection
@inject("offerService", "App\Services\OfferService" )
@extends('admin')

@section('content')
@section('header')
Showing {{ $product->name }}
@endsection
  <div class="row mb-12 g-6">
    <div class="col-md">
      <div class="card">
        <div class="row">
          <div class="col-md-4  ">
            <x-record-image :src="$product->image" :alt="$product->name" class="card-img card-img-left" />
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h1 class="card-title fs-4">{{ $product->name }}</h1>
              <p class="card-text"><strong>Description : </strong>{{ $product->desc }}</p>
              <p class="card-text"><strong>Price : </strong> ${{ $product->price }}</p>
              <p class="card-text"><strong>Current Price  </strong>${{ $offerService->getDiscount($product , $products_offers)["best"] }}</p>

              <p class="card-text"><strong>Int Price : </strong>${{ $product->int_price }}</p>
              <p class="card-text"><strong>Quantity : </strong>{{ $product->quantity }}</p>

              <p class="card-text"><strong>Tags : </strong> </p>
              @forelse($product->tags as $tag)
                <td><span class="badge bg-label-primary me-1">{{ $tag->name }}</span></td>
              @empty
                <p>Empty Tags</p>
              @endforelse
              <p class="card-text"><strong>Company : </strong> </p>

              @forelse($product->companies as $companies)
                <td><span class="badge bg-label-primary me-1">{{ $companies->name }}</span></td>
              @empty
                <span>None</span>

              @endforelse
              <p class="card-text"><small class="text-body-secondary"> <strong> Created At :
                  </strong>{{ $product->created_at }}</small></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
