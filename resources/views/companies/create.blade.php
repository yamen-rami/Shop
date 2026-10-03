@section("title")
  Create Comapny
@endsection
@extends('admin')

@section('content')
  <div class="row mb-6 gy-6">
    <!-- Basic Layout -->
    <div class="col-xxl">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Create company</h5>
          <small class="text-body-secondary float-end">company</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("company.store") }}" enctype="multipart/form-data">
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
                <x-form.remote-select resource="products" name="product_id" :selected="old('product_id', null)" placeholder="Search products" />
                @error('product_id')<p class="text-danger">{{ $message }}</p>@enderror
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
@endsection
@include('partials.select2-assets')
