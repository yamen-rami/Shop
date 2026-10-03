@section("title")
  Create Product
@endsection
@extends('admin')

@section('content')
  <div class="row mb-6 gy-6" >
    <!-- Basic Layout -->
    <div class="col-xxl">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Create Product</h5>
          <small class="text-body-secondary float-end">Product</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("product.store") }}" enctype="multipart/form-data" x-data="{ imagePreview: null }">
            @csrf
            {{-- ? Name --}}
            <x-form.input type="text" value="Name" feild="name"></x-form.input>
            {{-- ? Desc --}}
            <x-form.textarea type="text" value="Description" feild="desc"></x-form.textarea>
            {{-- ? Price --}}
            <x-form.input type="number" step="any" value="Price" feild="price"></x-form.input>
            <x-form.input type="number" step="any" value="Int Price" feild="int_price"></x-form.input>

            <x-form.input type="number" value="Quantity" feild="quantity"></x-form.input>
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Featured</label>
              <div class="col-sm-10">
                <input class="form-check-input" type="checkbox" name="featured" value="1"
                    @checked(old('featured', false))>
                @error("featured")
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
            </div>


            <x-form.input type="file" value="Image" feild="image" accept="image/*"
                x-on:change="if (imagePreview) URL.revokeObjectURL(imagePreview); imagePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null" />
            <img x-cloak x-show="imagePreview" x-bind:src="imagePreview" alt="Selected image preview" class="rounded mb-4" width="120">
            <div class="row mb-6  " >
              <label class="col-sm-2 col-form-label" for="basic-default-name">Select Tags</label>

              <div class="col-sm-10 col-lg-4 select2-primary" >
                <div>
                  <x-form.remote-select resource="tags" name="tags[]" multiple :selected="old('tags', [])" placeholder="Search tags" />
                </div>
                @error("tags")
                  <p class="text-danger">
                    {{ $message }}
                  </p>
                @enderror
              </div>

            </div>
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Select Catagory</label>
              <div class="col-sm-10">
                <div>
                  <x-form.remote-select resource="categories" name="catagory_id" :selected="old('catagory_id', null)" placeholder="Search categories" />
                </div>
                @error("catagory_id")
                  <p class="text-danger">
                    {{ $message }}
                  </p>
                @enderror
              </div>
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
