@extends('admin')

@section('content')
@section('title')
Category {{ $catagory->name }}
@endsection
@section('header')
Showing {{ $catagory->name }}
@endsection
  <div class="row mb-12 g-6">
    <div class="col-md">
      <div class="card">
        <div class="row">
          <div class="col-md-8">
            <div class="card-body">
              <h1 class="card-title fs-4">{{ $catagory->name }}</h1>
              <p class="card-text"><strong>Description : </strong>{{ $catagory->desc }}</p>
              <p class="card-text"><small class="text-body-secondary"> <strong> Created At :
                  </strong>{{ $catagory->created_at }}</small></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- TODO Catagory Offer Show --}}
  @if($offer)
    <div class="alert alert-success alert-dismissible mb-4" role="alert">
      <div class="d-flex gap-4">
        <div class="alert-icon flex-shrink-0 rounded me-0">
          <i class="icon-base ti tabler-percentage"></i>
        </div>
        <div class="flex-grow-1">
          <h5 class="alert-heading mb-1">{{ $offer->name }}</h5>
          <ul class="list-unstyled mb-0">
            @if ($offer->discount_type === 'percentage')
              <li>%{{ $offer->discount_value * 100 }} For All Catagory
                Products</li>
            @else
              <li>Fixed Amount ${{ $offer->discount_value }} For All
                Product Prices</li>
            @endif
          </ul>
        </div>
      </div>
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Id</th>
          <th>Name</th>
          <th>Catagory</th>
          <th>Image</th>
          <th>Desc</th>
          <th>Quantity</th>
          <th>Int Price</th>
          <th>Price</th>
          <th>Tags</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse ($products as $product)
          <tr>
            <td>{{ $product->id}}</td>
            <td>
              <span class="fw-medium">{{ $product->name }}</span>
            </td>
            <td>
              <span class="fw-medium">{{ $catagory->name }}</span>

            </td>
            <td>
              <ul class="list-unstyled m-0 avatar-group d-flex align-items-center">
                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                  class="avatar avatar-xs pull-up" title="{{ $product->name }}">
                  <x-record-image :src="$product->image?->path" :alt="$product->name" class="rounded-circle" />
                </li>
              </ul>
            </td>
            <td>{{ Str::limit($product->desc, 40) }}</td>
            <td><span class="badge bg-label-primary me-1">{{ $product->quantity }}</span></td>
            <td>{{ $product->int_price }}</td>
            <td>{{ $product->price }}</td>
            <td>
              <div class="d-flex">
                @foreach ($product->tags as $tag)
                  <a>
                    <button type="submit" class="badge bg-label-primary me-1">{{ $tag->name }}</button>
                  </a>
                @endforeach
              </div>
            </td>


            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="icon-base ti tabler-dots-vertical"></i>
                </button>
                <div class="dropdown-menu">
                  {{-- Show Product --}}
                  <a class="dropdown-item" href="{{ route('product.show', $product) }}"><i
                      class="icon-base ti tabler-pencil me-1"></i>
                    Show</a>
                  {{-- Edit Product --}}
                  <a class="dropdown-item" href="{{ route('product.edit', $product) }}"><i
                      class="icon-base ti tabler-pencil me-1"></i>
                    Edit</a>
                  {{-- Delete Product --}}
                  <form action="{{ route("product.destroy", $product->id) }}" method="POST">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="dropdown-item"><i class="icon-base ti tabler-trash me-1"></i>
                      Delete</button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="10" class="text-center py-4">No products use this category.</td></tr>
        @endforelse
      </tbody>
    </table>
    {{ $products->links()}}
  </div>
@endsection
