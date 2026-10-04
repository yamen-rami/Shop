@section("title")
  Edit company {{ $company->name }}
@endsection
@extends('admin')

@section('content')
@section('header')
Editing {{ $company->name }}
@endsection
  <div class="row mb-6 gy-6">
    <!-- Basic Layout -->
    <div class="col-xxl">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Edit Company</h5>
          <small class="text-body-secondary float-end">company</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("company.update", $company) }}" enctype="multipart/form-data">
            @csrf
            @method("PATCH")
            <div class="d-flex justify-content-between">
              <div>
                <h5>
                  Currnet Image
                </h5>
              </div>
              <div>
                <x-record-image :src="$company->image" :alt="$company->name" width="100px" class="img" />
              </div>
              <div></div>
            </div>
            {{-- ? Name --}}
            <x-form.input type="text" edit="{{ $company->name }}" value="Name" feild="name"></x-form.input>
            {{-- ? Desc --}}
            <x-form.textarea type="text" edit="{{ $company->desc }}" value="Description" feild="desc"></x-form.textarea>
            {{-- ? Price --}}
            <x-form.input type="file" value="Image" feild="image"></x-form.input>
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Select Products</label>
              <div class="col-sm-10 col-lg-4">
                <x-form.remote-select resource="products" name="product_id" :selected="old('product_id', $selectedProduct ?? null)" placeholder="Search products" />
                @error('product_id')<p class="text-danger">{{ $message }}</p>@enderror
              </div>
            </div>


            <div class="row justify-content-end">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Update </button>
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
