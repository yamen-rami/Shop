@extends('layouts.storefront')

@section('header')
Editing {{ $order->name }}
@endsection
@section("title")
  Edit Order {{ $order->name }}
@endsection
@section('content')
  <x-home.navbar />
  <main class="container section-space-p">
    @hasSection('header')
      <h1>@yield('header')</h1>
    @endif

  <div class="row mb-6 gy-6">
    <!-- Basic Layout -->
    <div class="col-xxl">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Create Order</h5>
          <small class="text-body-secondary float-end">Order</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("order.update" , $order) }}" >
            @method("PATCH")

            @csrf
            <div class="d-flex justify-content-between">
            </div>
            {{-- ? Name --}}
            <x-form.input type="text" edit="{{ $order->name }}" value="Name" feild="name"></x-form.input>
            {{-- ? Desc --}}
            {{-- ? Price --}}
            <x-form.input type="number" edit="{{ $order->price }}" value="Price" feild="price"></x-form.input>

            {{-- The Edit refrese to the actual value cause of the name that i have created before --}}
            <x-form.input type="number" edit="{{ $order->quantity }}" value="Quantity" feild="quantity"></x-form.input>
            {{-- Text Area --}}
            <x-form.textarea type="text" edit="{{ $order->location }}" value="Location" feild="location"></x-form.textarea>
            <div class="col-sm-10">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Select Product</label>

              <select data-user-select2 class="bg-black text-white mx-10 rounded mb-10" name="product_id">
                @if($order->products)
                  @forelse($order->products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                  @empty
                    <option value="">Select Product</option>
                  @endforelse
                @endif
                @foreach ($products as $product)
                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
                @error("product_id")
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </select>
            </div>
            <div class="row justify-content-end">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Edit Order</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Basic with Icons -->

  </div>
  </main>
  <x-footer />
  <x-home.menu />
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}">
@endpush

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
@endpush

@push('scripts')
  <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
  <script src="{{ asset('assets/js/user-selects.js') }}"></script>
@endpush
