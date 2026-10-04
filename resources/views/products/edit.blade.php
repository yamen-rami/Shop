@section("title")
  Edit Product {{ $product->name }}
@endsection
@extends('admin')

@section('content')

@section('header')
Editing {{ $product->name }}
@endsection
  <div class="row mb-6 gy-6">
    <!-- Basic Layout -->
    <div class="col-xxl">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Editing Product</h5>
          <small class="text-body-secondary float-end">Product</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("product.update", $product) }}" enctype="multipart/form-data" x-data="{ imagePreview: null }">
            @csrf
            @method("PATCH")
            <div class="d-flex justify-content-between">
              <div>
                <h5>
                  Currnet Image
                </h5>
              </div>
              <div>
                <x-record-image :src="$product->image" :alt="$product->name" width="100px" class="img" />
              </div>
              <div></div>
            </div>  
            {{-- ? Name --}}
            <x-form.input type="text" edit="{{ $product->name }}" value="Name" feild="name"></x-form.input>

            {{-- ? Desc --}}
            <x-form.textarea type="text" edit="{{ $product->desc }}" value="Description" feild="desc"></x-form.textarea>
            {{-- ? Price --}}
            <x-form.input type="number" step="any" edit="{{ $product->price }}" value="Price"
              feild="price"></x-form.input>
            <x-form.input type="number" step="any" edit="{{ $product->int_price }}" value="Int Price"
              feild="int_price"></x-form.input>
            {{-- The Edit refrese to the actual value cause of the name that i have created before --}}
            <x-form.input type="number" edit="{{ $product->quantity }}" value="Quantity"
              feild="quantity"></x-form.input>
            <x-form.input type="file" value="Image" feild="image" accept="image/*"
                x-on:change="if (imagePreview) URL.revokeObjectURL(imagePreview); imagePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null" />
            <img x-cloak x-show="imagePreview" x-bind:src="imagePreview" alt="Selected image preview" class="rounded mb-4" width="120">
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Featured</label>
              <div class="col-sm-10">
                <input class="form-check-input" type="checkbox" name="featured" value="1" @checked(old('featured', $product->featured))>
                @error("featured")
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
            </div>

            {{-- <x-form.input type="text" value="Tags" feild="tags"></x-form.input> --}}

            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Select Tags</label>
              <div class="col-sm-10">
                <x-form.remote-select resource="tags" name="tags[]" multiple :selected="old('tags', $selectedTags ?? [])" placeholder="Search tags" />
                @error("tags")
                  <p class="text-danger">
                    {{ $message }}
                  </p>
                @enderror
              </div>
            </div>
            {{-- ! Catagory --}}
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label " for="basic-default-name">Select Catagory</label>
              <div class="col-sm-10">
                <div>
                  <x-form.remote-select resource="categories" name="catagory_id" :selected="old('catagory_id', $product->catagory_id)" placeholder="Search categories" />
                </div>
                @error("catagory_id")
                  <p class="text-danger">
                    {{ $message }}
                  </p>
                @enderror
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
