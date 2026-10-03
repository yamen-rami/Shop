@extends('layouts.storefront')

@section("title")
  Create Comapny
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
          <h5 class="mb-0">Create contact</h5>
          <small class="text-body-secondary float-end">contact</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("contact.store") }}" enctype="multipart/form-data">
            @csrf
            {{-- ? Name --}}
            <x-form.input type="text" value="Name" feild="name"></x-form.input>
            {{-- ? Desc --}}
            <x-form.textarea type="text" value="Description" feild="desc"></x-form.textarea>
            {{-- ? Price --}}
            <x-form.input type="file" value="Image" feild="image"></x-form.input>
            <div>

            </div>
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Select Products</label>
              <div class="col-sm-10">
                <select data-user-select2 class="bg-black text-white" name="product_id">
                  <option value="">Select Products</option>
                  @foreach ($products as $product )
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="row justify-content-end">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Send</button>
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
